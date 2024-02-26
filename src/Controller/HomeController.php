<?php

namespace App\Controller;

use App\Entity\CategorieDepense;
use App\Entity\Mensualite;
use App\Entity\Prevues;
use App\Form\MensualiteType;
use App\Repository\CategorieDepenseRepository;
use App\Repository\CategorieRevenuRepository;
use App\Repository\DepenseRepository;
use App\Repository\MensualiteRepository;
use App\Repository\PrevuesRepository;
use App\Repository\RevenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MensualiteRepository $mensualiteRepository, DepenseRepository $depenseRepository,
                          RevenuRepository $revenuRepository, CategorieDepenseRepository $categorieDepenseRepository,
                          CategorieRevenuRepository $categorieRevenuRepository, PrevuesRepository $prevuesRepository,
                          Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $fiche = [];
        $nbFiche = 0;
        $ficheActif = $mensualiteRepository->findBy(['user' => $user, 'actif' => 1]);

        if (count($ficheActif) == 1) {
            $fiche = $ficheActif[0];
            $nbFiche = 1;
        } elseif (count($ficheActif) > 1) {
            $fiche = $ficheActif;
            $nbFiche = count($fiche);
        }

        $depense = [];
        $revenu = [];
        if ($nbFiche == 1) {

            /* TOUTES LES DEPENSE ET REVENU */
            foreach ($categorieDepenseRepository->findBy(['User' => $user]) as $categoriDepense) {
                $depenseAll = $depenseRepository->findBy(['mensualite' => $fiche->getId(), 'categorie' => $categoriDepense->getId()]);
                $prevuAll = $prevuesRepository->findBy(['mensualite' => $fiche->getId(), 'categorie' => $categoriDepense->getId()]);

                $totalMontant = 0;
                $totalPrevu = 0;
                foreach ($depenseAll as $d) {
                    $totalMontant = $totalMontant + $d->getMontant();
                }

                foreach ($prevuAll as $p) {
                    $totalPrevu = $totalPrevu + $p->getMontant();
                }

                $depense[$categoriDepense->getName()] =  ['reel' => ['detail' => $depenseAll, 'totalMontant' => $totalMontant], 'prevu' => ['detail' => $prevuAll, 'totalMontant' => $totalPrevu], 'diff' => ['totalMontant' => $totalPrevu - $totalMontant]];
            }

            foreach ($categorieRevenuRepository->findBy(['User' => $user]) as $categoriRevenu) {
                $revenuAll = $revenuRepository->findBy(['mensualite' => $fiche->getId(), 'categorie' => $categoriRevenu->getId()]);
                $prevuAll = $prevuesRepository->findBy(['mensualite' => $fiche->getId(), 'categorieRevenues' => $categoriRevenu->getId()]);

                $totalMontant = 0;
                $totalPrevu = 0;
                foreach ($revenuAll as $r) {
                    $totalMontant = $totalMontant + $r->getMontant();
                }

                foreach ($prevuAll as $p) {
                    $totalPrevu = $totalPrevu + $p->getMontant();
                }

                $revenu[$categoriRevenu->getName()] =  ['reel' => ['detail' => $revenuAll, 'totalMontant' => $totalMontant], 'prevu' => ['detail' => $prevuAll, 'totalMontant' => $totalPrevu], 'diff' => ['totalMontant' => $totalPrevu - $totalMontant]];

            }
        }

        /* CREATION FICHE */

        $mensualite = new Mensualite();
        $formMensualite = $this->createForm(MensualiteType::class, $mensualite);
        $formMensualite->handleRequest($request);

        if ($formMensualite->isSubmitted() && $formMensualite->isValid()) {
            $mensualite->setUser($user);
            $mensualite->setActif(1);

            $allRequest = $request->request->all();

            $entityManager->persist($mensualite);
            $entityManager->flush();

            foreach ($allRequest['depense'] as $key => $d) {
                $prevu = new Prevues();
                $prevu->setUser($user);
                $prevu->setMensualite($mensualite);
                $prevu->setCategorie($categorieDepenseRepository->findBy(['id' => $key])[0]);
                $prevu->setTitre($categorieDepenseRepository->findBy(['id' => $key])[0]->getName());
                $prevu->setMontant(floatval($d));

                $entityManager->persist($prevu);
                $entityManager->flush();
            }

            foreach ($allRequest['revenu'] as $key => $p) {
                $prevu = new Prevues();
                $prevu->setUser($user);
                $prevu->setMensualite($mensualite);
                $prevu->setCategorieRevenues($categorieRevenuRepository->findBy(['id' => $key])[0]);
                $prevu->setTitre($categorieRevenuRepository->findBy(['id' => $key])[0]->getName());
                $prevu->setMontant(floatval($p));

                $entityManager->persist($prevu);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_home', ['id' => $mensualite->getId()], Response::HTTP_SEE_OTHER);
        }


        return $this->render('home/index.html.twig', [
            'nbFicheActive' => $nbFiche,
            'fiche' => $fiche,
            'depense' => $depense,
            'revenu' => $revenu,

            'formMensualite' => $formMensualite,
            'mensualite' => $mensualite,

            'categorieDepense' => $categorieDepenseRepository->findBy(['User' => $user]),
            'categorieRevenu' => $categorieRevenuRepository->findBy(['User' => $user]),
        ]);
    }
}

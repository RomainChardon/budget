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
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MensualiteRepository $mensualiteRepository, DepenseRepository $depenseRepository,
                          RevenuRepository $revenuRepository, CategorieDepenseRepository $categorieDepenseRepository,
                          CategorieRevenuRepository $categorieRevenuRepository, PrevuesRepository $prevuesRepository,
                          Request $request, EntityManagerInterface $entityManager, ChartBuilderInterface $chartBuilder): Response
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

        $labelDepense = [];
        $statDepense = [];
        if ($nbFiche == 1) {

            /* TOUTES LES DEPENSE ET REVENU */
            foreach ($categorieDepenseRepository->findBy(['User' => $user]) as $categoriDepense) {
                array_push($labelDepense, $categoriDepense->getName());
                $depenseAll = $depenseRepository->findBy(['mensualite' => $fiche->getId(), 'categorie' => $categoriDepense->getId()]);
                $prevuAll = $prevuesRepository->findBy(['mensualite' => $fiche->getId(), 'categorie' => $categoriDepense->getId()]);

                $totalMontant = 0;
                $totalPrevu = 0;
                foreach ($depenseAll as $d) {
                    array_push($statDepense, $d->getMontant());
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

                $revenu[$categoriRevenu->getName()] =  ['reel' => ['detail' => $revenuAll, 'totalMontant' => $totalMontant], 'prevu' => ['detail' => $prevuAll, 'totalMontant' => $totalPrevu], 'diff' => ['totalMontant' => $totalMontant - $totalPrevu]];

            }
        }

        /* CHART JS */

        $chartPrevuDepense = $chartBuilder->createChart(Chart::TYPE_BAR);

        $ttPrevu = 0;
        $ttReel = 0;

        foreach ($depense as $d) {
            $ttPrevu = $ttPrevu + $d['prevu']['totalMontant'];
        }

        foreach ($depense as $d) {
            $ttReel = $ttReel + $d['reel']['totalMontant'];
        }

        $chartPrevuDepense->setData([
            'labels' => ['Prévue', 'Réel'],
            'datasets' => [
                [
                    'backgroundColor' => [
                            'rgb(255, 159, 64)',
                            'rgb(153, 102, 255)',
                        ],
                    'data' => [
                        $ttPrevu,
                        $ttReel
                    ]
                ],
            ],
        ]);

        $chartPrevuDepense->setOptions([
            'plugins' => [
                'legend' => false
            ],
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                ],
            ],
        ]);

        $chartPrevuRevenu = $chartBuilder->createChart(Chart::TYPE_BAR);

        $ttPrevu = 0;
        $ttReel = 0;

        foreach ($revenu as $d) {
            $ttPrevu = $ttPrevu + $d['prevu']['totalMontant'];
        }

        foreach ($revenu as $d) {
            $ttReel = $ttReel + $d['reel']['totalMontant'];
        }

        $chartPrevuRevenu->setData([
            'labels' => ['Prévue', 'Réel'],
            'datasets' => [
                [
                    'backgroundColor' => [
                        'rgb(201, 203, 207)',
                        'rgb(255, 205, 86)',
                    ],
                    'data' => [
                        $ttPrevu,
                        $ttReel
                    ]
                ],
            ],
        ]);

        $chartPrevuRevenu->setOptions([
            'plugins' => [
                'legend' => false
            ],
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                ],
            ],
        ]);

        $chartDepense = $chartBuilder->createChart(Chart::TYPE_PIE);

        $chartDepense->setData([
            'labels' => $labelDepense,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 159, 64)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 205, 86)',
                        'rgb(201, 203, 207)',
                    ],
                    'data' => $statDepense
                ],
            ],
        ]);

        $chartDepense->setOptions([
            'plugins' => [
                'legend' => false
            ],
        ]);

        $chartSolde = $chartBuilder->createChart(Chart::TYPE_BAR);

        $ttDepense = 0;
        $ttRevenu = 0;

        foreach ($depense as $d) {
            $ttDepense = $ttDepense + $d['reel']['totalMontant'];
        }

        foreach ($revenu as $r) {
            $ttRevenu = $ttRevenu + $r['reel']['totalMontant'];
        }

        $chartSolde->setData([
            'labels' => ['Solde départ', 'Solde actuel'],
            'datasets' => [
                [
                    'backgroundColor' => [
                        'rgb(177, 4, 15)',
                        'rgb(30, 215, 96)',
                    ],
                    'data' => [
                        $fiche->getSoldeDepart(),
                        $fiche->getSoldeDepart() + ($ttRevenu - $ttDepense),
                    ]
                ],
            ],
        ]);

        $chartSolde->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => false
            ],
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => $fiche->getSoldeDepart(),
                ],
            ],
        ]);

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

        $lastPrevuDepense = $categorieDepenseRepository->findBy(['User' => $user]);
        $lastPrevuRevenu = $categorieRevenuRepository->findBy(['User' => $user]);

        $categoriePrevu = [
            'depense' => [],
            'revenu' => []
        ];
        foreach ($lastPrevuDepense as $d) {
            $nb = count($d->getPrevues()->toArray());
            array_push($categoriePrevu['depense'], ['categorie' => $d, 'last' => $d->getPrevues()->toArray()[$nb - 1]]);
        }

        foreach ($lastPrevuRevenu as $r) {
            $nb = count($r->getPrevues()->toArray());
            array_push($categoriePrevu['revenu'], ['categorie' => $r, 'last' => $r->getPrevues()->toArray()[$nb - 1]]);
        }


        return $this->render('home/index.html.twig', [
            'nbFicheActive' => $nbFiche,
            'fiche' => $fiche,
            'depense' => $depense,
            'revenu' => $revenu,

            'formMensualite' => $formMensualite,
            'mensualite' => $mensualite,

            'categoriePrevu' => $categoriePrevu,

            'chartPrevuDepense' => $chartPrevuDepense,
            'chartPrevuRevenu' => $chartPrevuRevenu,
            'chartDepense' => $chartDepense,
            'chartSolde' => $chartSolde,
        ]);
    }
}

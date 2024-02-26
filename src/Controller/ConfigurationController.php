<?php

namespace App\Controller;

use App\Entity\Mensualite;
use App\Entity\Prevues;
use App\Form\PrevuesType;
use App\Repository\CategorieDepenseRepository;
use App\Repository\CategorieRevenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfigurationController extends AbstractController
{
    #[Route('/configuration', name: 'app_configuration')]
    public function index(CategorieDepenseRepository $categorieDepenseRepository, CategorieRevenuRepository $categorieRevenuRepository): Response
    {
        $user = $this->getUser();

        return $this->render('configuration/index.html.twig', [
            'controller_name' => 'ConfigurationController',
            'categorieDepense' => $categorieDepenseRepository->findBy(['User' => $user->getId()]),
            'categorieRevenu' => $categorieRevenuRepository->findBy(['User' => $user->getId()]),
        ]);
    }

    #[Route('/new_all/{id}', name: 'app_prevues_new_all', methods: ['GET', 'POST'])]
    public function newAll(Mensualite $mensualite, Request $request, EntityManagerInterface $entityManager,
                           CategorieDepenseRepository $categorieDepenseRepository, CategorieRevenuRepository $categorieRevenuRepository): Response
    {
        $user = $this->getUser();

        $prevue = new Prevues();
        $form = $this->createForm(PrevuesType::class, $prevue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prevue->setUser($user);
            $prevue->setMensualite($mensualite);

            $categorieDepenseSelect = $request->request->get('selectDepense');
            $categorieRevenuSelect = $request->request->get('selectRevenu');

            if ($categorieDepenseSelect != '') {
                $prevue->setCategorie($categorieDepenseRepository->findBy(['id' => $categorieDepenseSelect])[0]);
            } elseif ($categorieRevenuSelect != '') {
                $prevue->setCategorieRevenues($categorieRevenuRepository->findBy(['id' => $categorieRevenuSelect])[0]);
            }

            $entityManager->persist($prevue);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prevues/new.html.twig', [
            'prevue' => $prevue,
            'form' => $form,
            'categorieDepense' => $categorieDepenseRepository->findBy(['User' => $user]),
            'categorieRevenu' => $categorieRevenuRepository->findBy(['User' => $user]),
        ]);
    }
}

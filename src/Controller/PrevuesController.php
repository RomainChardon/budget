<?php

namespace App\Controller;

use App\Entity\Mensualite;
use App\Entity\Prevues;
use App\Form\PrevuesType;
use App\Repository\CategorieDepenseRepository;
use App\Repository\CategorieRevenuRepository;
use App\Repository\PrevuesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prevues')]
class PrevuesController extends AbstractController
{
    #[Route('/', name: 'app_prevues_index', methods: ['GET'])]
    public function index(PrevuesRepository $prevuesRepository): Response
    {
        return $this->render('prevues/index.html.twig', [
            'prevues' => $prevuesRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_prevues_new', methods: ['GET', 'POST'])]
    public function new(Mensualite $mensualite, Request $request, EntityManagerInterface $entityManager,
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

    #[Route('/{id}', name: 'app_prevues_show', methods: ['GET'])]
    public function show(Prevues $prevue): Response
    {
        return $this->render('prevues/show.html.twig', [
            'prevue' => $prevue,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prevues_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prevues $prevue, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrevuesType::class, $prevue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prevues_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prevues/edit.html.twig', [
            'prevue' => $prevue,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prevues_delete', methods: ['POST'])]
    public function delete(Request $request, Prevues $prevue, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prevue->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prevue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prevues_index', [], Response::HTTP_SEE_OTHER);
    }
}

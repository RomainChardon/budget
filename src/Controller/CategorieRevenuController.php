<?php

namespace App\Controller;

use App\Entity\CategorieRevenu;
use App\Form\CategorieRevenuType;
use App\Repository\CategorieRevenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorie/revenu')]
class CategorieRevenuController extends AbstractController
{
    #[Route('/', name: 'app_categorie_revenu_index', methods: ['GET'])]
    public function index(CategorieRevenuRepository $categorieRevenuRepository): Response
    {
        return $this->render('categorie_revenu/index.html.twig', [
            'categorie_revenus' => $categorieRevenuRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_revenu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieRevenu = new CategorieRevenu();
        $form = $this->createForm(CategorieRevenuType::class, $categorieRevenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieRevenu);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_revenu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_revenu/new.html.twig', [
            'categorie_revenu' => $categorieRevenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_revenu_show', methods: ['GET'])]
    public function show(CategorieRevenu $categorieRevenu): Response
    {
        return $this->render('categorie_revenu/show.html.twig', [
            'categorie_revenu' => $categorieRevenu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_revenu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieRevenu $categorieRevenu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieRevenuType::class, $categorieRevenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_revenu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_revenu/edit.html.twig', [
            'categorie_revenu' => $categorieRevenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_revenu_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieRevenu $categorieRevenu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieRevenu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieRevenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_revenu_index', [], Response::HTTP_SEE_OTHER);
    }
}

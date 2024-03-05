<?php

namespace App\Controller;

use App\Entity\Mensualite;
use App\Entity\Revenu;
use App\Form\RevenuType;
use App\Repository\RevenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/revenu')]
class RevenuController extends AbstractController
{
    #[Route('/{id}', name: 'app_revenu_index', methods: ['GET'])]
    public function index(Mensualite $mensualite, RevenuRepository $revenuRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $revenu = new Revenu();
        $form = $this->createForm(RevenuType::class, $revenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revenu->setMensualite($mensualite);

            $entityManager->persist($revenu);
            $entityManager->flush();

            return $this->redirectToRoute('app_revenu_index', ['id' => $mensualite], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revenu/index.html.twig', [
            'revenus' => $revenuRepository->findAll(),
            'revenu' => $revenu,
            'form' => $form,
        ]);
    }

    #[Route('/new/{id}', name: 'app_revenu_new', methods: ['GET', 'POST'])]
    public function new(Mensualite $mensualite, Request $request, EntityManagerInterface $entityManager): Response
    {
        $revenu = new Revenu();
        $form = $this->createForm(RevenuType::class, $revenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revenu->setMensualite($mensualite);

            $entityManager->persist($revenu);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revenu/new.html.twig', [
            'revenu' => $revenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_revenu_show', methods: ['GET'])]
    public function show(Revenu $revenu): Response
    {
        return $this->render('revenu/show.html.twig', [
            'revenu' => $revenu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_revenu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Revenu $revenu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RevenuType::class, $revenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_revenu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('revenu/edit.html.twig', [
            'revenu' => $revenu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_revenu_delete', methods: ['POST'])]
    public function delete(Request $request, Revenu $revenu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revenu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($revenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_revenu_index', [], Response::HTTP_SEE_OTHER);
    }
}

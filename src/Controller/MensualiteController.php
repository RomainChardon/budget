<?php

namespace App\Controller;

use App\Entity\Mensualite;
use App\Form\MensualiteType;
use App\Repository\MensualiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mensualite')]
class MensualiteController extends AbstractController
{
    #[Route('/', name: 'app_mensualite_index', methods: ['GET'])]
    public function index(MensualiteRepository $mensualiteRepository): Response
    {
        return $this->render('mensualite/index.html.twig', [
            'mensualites' => $mensualiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mensualite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mensualite = new Mensualite();
        $form = $this->createForm(MensualiteType::class, $mensualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mensualite);
            $entityManager->flush();

            return $this->redirectToRoute('app_mensualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mensualite/new.html.twig', [
            'mensualite' => $mensualite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mensualite_show', methods: ['GET'])]
    public function show(Mensualite $mensualite): Response
    {
        return $this->render('mensualite/show.html.twig', [
            'mensualite' => $mensualite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mensualite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mensualite $mensualite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MensualiteType::class, $mensualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mensualite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mensualite/edit.html.twig', [
            'mensualite' => $mensualite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mensualite_delete', methods: ['POST'])]
    public function delete(Request $request, Mensualite $mensualite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mensualite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mensualite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mensualite_index', [], Response::HTTP_SEE_OTHER);
    }
}

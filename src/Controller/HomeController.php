<?php

namespace App\Controller;

use App\Repository\MensualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MensualiteRepository $mensualiteRepository): Response
    {
        $ficheActif = $mensualiteRepository->findBy(['user' => $user = $this->getUser(), 'actif' => 1]);

        return $this->render('home/index.html.twig', [
            'ficheActif' => $ficheActif,
        ]);
    }
}

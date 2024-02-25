<?php

namespace App\Controller;

use App\Repository\CategorieDepenseRepository;
use App\Repository\CategorieRevenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}

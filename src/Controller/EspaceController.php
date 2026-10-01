<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EspaceController extends AbstractController
{
    #[Route('/espace', name: 'app_espace')]
    public function index(): Response
    {
        return $this->render('espace/index.html.twig', [
            'controller_name' => 'EspaceController',
        ]);
    }
}

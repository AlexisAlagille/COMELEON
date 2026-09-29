<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PrestationRepository;

final class PrestationsController extends AbstractController
{
    #[Route('/prestations', name: 'app_prestations')]
    public function prestations(PrestationRepository $repository): Response
    {
        $prestations = $repository->findAll();

        return $this->render('prestations/index.html.twig', [
            'controller_name' => 'PrestationsController',
            'prestations' => $prestations
        ]);
    }
}

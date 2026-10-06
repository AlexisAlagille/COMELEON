<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(PrestationRepository $repository, AvisRepository $avisRepository): Response
    {
        $prestations = $repository->findAll();
        $derniersAvis = $avisRepository->findDerniersAvisAvecAuteur(10);

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'prestations' => $prestations,
            'avis' => $derniersAvis,
        ]);
    }
}
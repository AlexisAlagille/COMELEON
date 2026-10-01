<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier', methods: ['GET'])]
    public function index(PanierService $panier): Response
    {
        $prestations = $panier->getPrestations();
        $total = array_sum(array_map(
            static fn ($prestation): float => $prestation->getPrix() ?? 0.0,
            $prestations,
        ));

        return $this->render('panier/index.html.twig', [
            'prestations' => $prestations,
            'total' => $total,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter', requirements: ['id' => '\\d+'], methods: ['POST'])]
    public function ajouter(int $id, Request $request, PrestationRepository $repository, PanierService $panier): Response
    {
        if (!$this->isCsrfTokenValid('panier_ajouter_'.$id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $prestation = $repository->find($id);
        if (!$prestation) {
            throw $this->createNotFoundException('Prestation introuvable.');
        }

        $added = $panier->add($id);
        $this->addFlash(
            'success',
            $added ? 'Prestation ajoutée au panier.' : 'Cette prestation est déjà dans votre panier.',
        );

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/retirer/{id}', name: 'app_panier_retirer', requirements: ['id' => '\\d+'], methods: ['POST'])]
    public function retirer(int $id, Request $request, PanierService $panier): Response
    {
        if (!$this->isCsrfTokenValid('panier_retirer_'.$id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $panier->remove($id);
        $this->addFlash('success', 'Prestation retirée du panier.');

        return $this->redirectToRoute('app_panier');
    }
}

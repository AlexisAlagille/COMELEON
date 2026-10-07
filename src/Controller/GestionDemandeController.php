<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DemandeRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class GestionDemandeController extends AbstractController
{
    #[Route('/espace/demande/{id}/modifier', name: 'app_gestion_demande')]
    public function modifier($id, Request $request, DemandeRepository $demandeRepository, StatutRepository $statutRepository, EntityManagerInterface $em): Response
    {
        //! Page réservée aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //! On cherche la demande avec son id
        $demande = $demandeRepository->find($id);
        if ($demande == null) {
            throw $this->createNotFoundException('Demande introuvable');
        }

        //! Formulaire envoyé : on change le statut
        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('modifier_demande_' . $id, $request->request->get('_token'))) {
                $statut = $statutRepository->find($request->request->get('statut'));

                if ($statut != null) {
                    $demande->setStatut($statut);
                    $em->flush();

                    return $this->redirectToRoute('app_espace');
                }
            }
        }

        return $this->render('gestion_demande/index.html.twig', [
            'demande' => $demande,
            'statuts' => $statutRepository->findAll(),
        ]);
    }
}

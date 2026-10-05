<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\DemandeRepository;


final class EspaceController extends AbstractController
{
    #[Route('/espace', name: 'app_espace')]
    public function index(DemandeRepository $demandeRepository): Response
    {
        //! Utilisateur actuellement connecté
        $user = $this->getUser();

        //! Toutes les demandes de cet utilisateur
        $demandes = $demandeRepository->recupererDemandesParUtilisateur($user);

        //! Les libellés des statuts, transformés en liste simple : ['En cours', 'Terminée', ...]
        $statuts = array_column(
            $demandeRepository->RecupererStatutDemandeParUtilisateur($user),
            'libelleStatut'
        );

        //! Les clés sont les noms utilisés dans le template Twig
        return $this->render('espace/index.html.twig', [
            'demandes' => $demandes,
            'statuts' => $statuts,
        ]);
    }
}

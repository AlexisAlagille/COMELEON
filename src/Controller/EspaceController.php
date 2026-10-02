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

        //! La clé 'demandes' est le nom utilisé dans la boucle Twig
        return $this->render('espace/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }
}

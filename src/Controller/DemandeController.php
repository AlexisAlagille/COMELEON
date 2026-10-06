<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\User;
use App\Form\DemandeType;
use App\Repository\StatutRepository;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class DemandeController extends AbstractController
{
    #[Route('/demande', name: 'app_demande')]
    #[IsGranted('ROLE_CLIENT')]
    public function demande(
        Request $request,
        EntityManagerInterface $entityManager,
        StatutRepository $statutRepository,
        PanierService $panier,
    ): Response {
        $prestations = $panier->getPrestations();
        if ($prestations === []) {
            $this->addFlash('success', 'Votre panier est vide. Ajoutez une prestation avant de créer une demande.');
            return $this->redirectToRoute('app_panier');
        }

        $demande = new Demande();
        foreach ($prestations as $prestation) {
            $demande->addPrestation($prestation);
        }

        $form = $this->createForm(DemandeType::class, $demande);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user instanceof User) {
                throw $this->createAccessDeniedException();
            }

            $statut = $statutRepository->findOneBy(['libelleStatut' => 'En attente']);
            if (!$statut) {
                throw new \LogicException("Le statut 'En attente' est absent de la base de données.");
            }

            $demande->setUser($user);
            $demande->setStatut($statut);
            $entityManager->persist($demande);
            $entityManager->flush();
            $panier->clear();

            $this->addFlash('success', 'Votre demande a bien été envoyée.');

            return $this->redirectToRoute('app_panier');
        }

        return $this->render('demande/index.html.twig', [
            'form' => $form,
            'prestations' => $prestations,
        ]);
    }
}
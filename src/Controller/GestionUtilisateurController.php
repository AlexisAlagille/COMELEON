<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Avis;

final class GestionUtilisateurController extends AbstractController
{
    #[Route('/gestion/utilisateur', name: 'app_gestion_utilisateur')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($request->isMethod('POST')) {
            $user = $userRepository->find($request->request->getInt('user'));
            $role = $roleRepository->find($request->request->getInt('role'));
            $token = $request->request->getString('_token');

            if (!$this->isCsrfTokenValid('modifier_role_' . $request->request->getInt('user'), $token)) {
                throw $this->createAccessDeniedException('Jeton CSRF invalide.');
            }

            if ($user === null || $role === null) {
                throw $this->createNotFoundException('Utilisateur ou rôle introuvable.');
            }

            if ($user === $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez pas modifier votre propre rôle.');

                return $this->redirectToRoute('app_gestion_utilisateur');
            }

            if (!in_array(strtolower((string) $role->getLibelleRole()), ['admin', 'client'], true)) {
                throw $this->createAccessDeniedException('Ce rôle ne peut pas être attribué ici.');
            }

            $user->setRole($role);
            $entityManager->flush();
            $this->addFlash('success', 'Le rôle de l’utilisateur a été mis à jour.');

            return $this->redirectToRoute('app_gestion_utilisateur');
        }

        $roles = array_filter(
            $roleRepository->findAll(),
            static fn ($role): bool => in_array(strtolower((string) $role->getLibelleRole()), ['admin', 'client'], true),
        );

        return $this->render('gestion_utilisateur/index.html.twig', [
            'utilisateurs' => $userRepository->findAll(),
            'roles' => $roles,
        ]);
    }


#[Route('/gestion/utilisateur/{id}/supprimer', name: 'app_gestion_utilisateur_supprimer', methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
public function supprimer(
    int $id,
    Request $request,
    UserRepository $userRepository,
    EntityManagerInterface $entityManager,
): Response {
    $utilisateur = $userRepository->find($id);

    if ($utilisateur === null) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }

    if (!$this->isCsrfTokenValid(
        'supprimer_utilisateur_' . $id,
        $request->request->getString('_token'),
    )) {
        throw $this->createAccessDeniedException('Jeton CSRF invalide.');
    }

    if ($utilisateur === $this->getUser()) {
        $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte.');

        return $this->redirectToRoute('app_gestion_utilisateur');
    }

    $avisASupprimer = [];
    $avisRepository = $entityManager->getRepository(Avis::class);

    // Avis écrits par cet utilisateur, même sur les demandes d'autres personnes.
    foreach ($avisRepository->findBy(['user' => $utilisateur]) as $avis) {
        $avisASupprimer[$avis->getId()] = $avis;
    }

    // Tous les avis sur ses demandes, y compris ceux écrits par d'autres personnes.
    foreach ($utilisateur->getDemandes() as $demande) {
        foreach ($demande->getAvis() as $avis) {
            $avisASupprimer[$avis->getId()] = $avis;
        }
    }

    foreach ($avisASupprimer as $avis) {
        $entityManager->remove($avis);
    }

    foreach ($utilisateur->getDemandes() as $demande) {
        $entityManager->remove($demande);
    }

    $entityManager->remove($utilisateur);
    $entityManager->flush();

    $this->addFlash('success', 'Le compte et ses données liées ont été supprimés.');

    return $this->redirectToRoute('app_gestion_utilisateur');
}
}

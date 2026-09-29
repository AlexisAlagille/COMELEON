<?php

namespace App\Controller;

use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        RoleRepository $roleRepository,
    ): Response {
        $dto = new UserRegistrationDto();
        $form = $this->createForm(RegistrationType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $roleRepository->findOneBy(['libelleRole' => 'Client']);
            if (!$role) {
                throw new \LogicException("Le rôle par défaut n'existe pas en base.");
            }

            $user = new User();
            $user->setEmail($dto->email);
            $user->setNom($dto->nom);
            $user->setPrenom($dto->prenom);
            $user->setTelephone($dto->telephone);
            $user->setPassword($hasher->hashPassword($user, $dto->plainPassword));
            $user->setDateInscription(new \DateTimeImmutable());
            $user->setRole($role);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a bien été créé.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form,
        ]);
    }
}

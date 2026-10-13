<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Demande;
use App\Entity\Prestation;
use App\Entity\User;
use App\Entity\Avis;
use App\Form\PrestationType;
use App\Form\AvisType;
use App\Repository\DemandeRepository;
use App\Repository\PrestationRepository;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;


final class EspaceController extends AbstractController
{
    #[Route('/espace', name: 'app_espace')]
    public function index(DemandeRepository $demandeRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $demandes = $demandeRepository->recupererToutesLesDemandes();
        } else {
            $user = $this->getUser();

            $demandes = $demandeRepository->recupererDemandesParUtilisateur($user);
        }

        $statuts = array_map(
            static fn (Demande $demande): ?string => $demande->getStatut()?->getLibelleStatut(),
            $demandes
        );

        //! Les clés sont les noms utilisés dans le template Twig
        return $this->render('espace/index.html.twig', [
            'demandes' => $demandes,
            'statuts' => $statuts,
        ]);
    }
    #[Route('/espace/prestation', name: 'app_espace_prestation')]
    public function prestations(PrestationRepository $repository): Response
    {
        //! Toutes les prestations
        $prestation = $repository->findAll();

        return $this->render('espace/CRUD Prestations/showPrestations.html.twig', [
            'controller_name' => 'EspaceController',
            'prestations' => $prestation
        ]);
    }

    #[Route('/espace/prestation/ajouter', name: 'app_espace_prestation_ajouter')]
    public function addPrestation(
        Request $request,
        EntityManagerInterface $em,
        // Récupère le chemin racine du projet + /public/assets/img/prestations
        #[Autowire('%kernel.project_dir%/public/assets/img/prestations')]
        string $prestationImagesDirectory
    ): Response
    {
        //! Page réservée aux administrateurs
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $prestation = new Prestation();
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            $extension = $imageFile->guessExtension();

            $imageName = bin2hex(random_bytes(16)) . '.' . $extension;
            $imageFile->move($prestationImagesDirectory, $imageName);

            $prestation = $form->getData();
            $prestation->setImage($imageName);
            $em->persist($prestation);
            $em->flush();

            $this->addFlash('success', 'La prestation a bien été créée.');

            return $this->redirectToRoute('app_espace_prestation');
        }

        return $this->render('espace/CRUD Prestations/crudPrestations.html.twig', [
            'form' => $form,
            'is_edit' => false,
        ]);
    }

    #[Route('/espace/prestation/{id}/modifier', name: 'app_espace_prestation_modifier')]
    public function editPrestation(
        int $id,
        PrestationRepository $repository,
        Request $request,
        EntityManagerInterface $em,
        #[Autowire('%kernel.project_dir%/public/assets/img/prestations')]
        string $prestationImagesDirectory
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $prestation = $repository->find($id);

        $form = $this->createForm(PrestationType::class, $prestation, [
            'is_edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile instanceof UploadedFile) {
                $extension = $imageFile->guessExtension();
                // On créer un nom d'image aléatoire pour éviter deux image au même nom créant un conflit //
                $imageName = bin2hex(random_bytes(16)) . '.' . $extension;
                $imageFile->move($prestationImagesDirectory, $imageName);
                $prestation->setImage($imageName);
            }

            $em->flush();
            $this->addFlash('success', 'La prestation a bien été modifiée.');

            return $this->redirectToRoute('app_espace_prestation');
        }

        return $this->render('espace/CRUD Prestations/crudPrestations.html.twig', [
            'form' => $form,
            'is_edit' => true,
        ]);
    }

    #[Route('/espace/prestation/{id}/supprimer', name: 'app_espace_prestation_supprimer')]
    public function deletePrestation(
        int $id,
        PrestationRepository $repository,
        Request $request,
        EntityManagerInterface $em,
        #[Autowire('%kernel.project_dir%/public/assets/img/prestations')]
        string $prestationImagesDirectory
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$this->isCsrfTokenValid('supprimer_prestation_'.$id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide.');
        }

        $prestation = $repository->find($id);
        if (!$prestation) {
            throw $this->createNotFoundException('Prestation introuvable.');
        }

        if ($prestation->hasDemandesNonTerminees()) {
            $this->addFlash('error', 'Cette prestation ne peut pas être supprimée car elle est utilisée dans une ou plusieurs demandes non terminées.');

            return $this->redirectToRoute('app_espace_prestation');
        }

        $imageName = $prestation->getImage();
        $em->remove($prestation);
        $em->flush();

        $imagePath = $prestationImagesDirectory . DIRECTORY_SEPARATOR . $imageName;
        $imageDeleted = !$imageName || (
            basename($imageName) === $imageName
            && (!is_file($imagePath) || @unlink($imagePath))
        );
        $this->addFlash(
            $imageDeleted ? 'success' : 'error',
            $imageDeleted
                ? 'La prestation a bien été supprimée.'
                : "La prestation a été supprimée, mais son image n'a pas pu être supprimée."
        );

        return $this->redirectToRoute('app_espace_prestation');
    }

    #[Route('/espace/avis', name: 'app_espace_avis')]
    public function avis(PrestationRepository $prestationRepository,AvisRepository $avisRepository): Response
    {
        $prestations = $prestationRepository->findAll();
        $avis = $avisRepository->findAvis();

        return $this->render('espace/CRUD Avis/showAvis.html.twig', [
            'controller_name' => 'EspaceController',
            'prestations' => $prestations,
            'avis' => $avis,
        ]);
    }

    #[Route('/espace/avis/{id}/ajouter', name: 'app_espace_avis_ajouter')]
    public function addAvis(
        int $id,
        Request $request,
        DemandeRepository $demandeRepository,
        EntityManagerInterface $em
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $demande = $demandeRepository->find($id);
        if (!$demande) {
            throw $this->createNotFoundException('Demande introuvable.');
        }
        if ($demande->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setUser($user);
            $avis->setDemande($demande);
            $avis->setDateAvis(new \DateTimeImmutable());
            $em->persist($avis);
            $em->flush();

            $this->addFlash("success", "L'avis a bien été créée.");

            return $this->redirectToRoute('app_espace');
        }
        return $this->render('espace/CRUD Avis/addAvis.html.twig', [
            'form' => $form
        ]);
    }
}
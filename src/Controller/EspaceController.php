<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\DemandeRepository;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
            if (!$imageFile instanceof UploadedFile) {
                throw new \LogicException('Le formulaire valide doit contenir une image.');
            }

            $extension = $imageFile->guessExtension();
            if ($extension === null) {
                throw new \RuntimeException('Impossible de déterminer le format de l’image envoyée.');
            }

            $imageName = bin2hex(random_bytes(16)) . '.' . $extension;
            $imageFile->move($prestationImagesDirectory, $imageName);

            $prestation = $form->getData();
            $prestation->setImage($imageName);
            $em->persist($prestation);
            $em->flush();

            $this->addFlash('success', 'La prestation a bien été créée.');

            return $this->redirectToRoute('app_espace_prestation');
        }

        return $this->render('espace/CRUD Prestations/addPrestations.html.twig', [
            'form' => $form,
        ]);
    }
}

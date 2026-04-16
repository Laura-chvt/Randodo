<?php

namespace App\Controller;

use App\Entity\Hike;
use App\Entity\HikeDone;
use App\Form\HikeCreateFormType;
use App\Form\HikeDoneFormType;
use App\Repository\HikeRepository;
use App\Repository\LocationRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/hike', name: 'app_hike_')]
final class HikeController extends AbstractController
{   
    /**
    * Controller et affichage de la page home
    */
    #[Route('/', name: 'index')]
    public function index(HikeRepository $hikeRepository, Request $request): Response
    {
        
        $search = $request->query->get('q');

        if ($search) {
            $arrHike = $hikeRepository->search($search);
        } else {
            $arrHike = $hikeRepository->findAll();
        }

        return $this->render('hike/index.html.twig', [
            'hikeList'          => $arrHike,
        ]);
    }

    /**
    * Controller et affichage de la page d'une randonnée
    */
     #[Route('/{id<\d+>}', name: 'show')]
    public function show(Hike $hike): Response
    {
        $doneForm = $this->createForm(HikeDoneFormType::class);

        return $this->render('hike/show.html.twig', [
            'hike'       => $hike,
            'doneForm' => $doneForm
        ]);
    }

    /**
    * Controller et affichage de la page de création d'une randonnée
    * Accès : modérateur et plus
    */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_MODO')]
    public function create(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {

        $objNewHike = new Hike();

        $createForm = $this->createForm(HikeCreateFormType::class, $objNewHike);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $imageFile = $createForm->get('thumbnail')->getData();
            if ($imageFile) {
                $newFilename = $fileUploader->upload($imageFile);  
                $objNewHike->setThumbnail($newFilename);
            }

            $entityManager->persist($objNewHike);
            $entityManager->flush();

            $this->addFlash('success', "La randonnée a bien été créée");

            return $this->redirectToRoute('app_hike_show', [
                'id' => $objNewHike->getId()
            ]);
        }

        return $this->render('hike/form.html.twig', [
            'createForm'    => $createForm
        ]);
    }

    /**
    * Controller et affichage de la page de modification d'une randonnée
    * Accès : modérateur et plus
    */
     #[Route('/{id<\d+>}/update', name: 'update')] 
    #[IsGranted('ROLE_MODO')]
    public function update(Hike $hike, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        
        $updateForm = $this->createForm(HikeCreateFormType::class, $hike);
        $updateForm->handleRequest($request);
        if($updateForm->isSubmitted() && $updateForm->isValid()) {
            $imageFile = $updateForm->get('thumbnail')->getData();
            if ($imageFile) {
                if ($hike->getThumbnail()) {
                    $fileUploader->remove($hike->getThumbnail());
                }
            $newFilename = $fileUploader->upload($imageFile);
            $hike->setThumbnail($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', "La randonnée a bien été modifiée");

            return $this->redirectToRoute('app_hike_show', [
                'id' => $hike->getId()
            ]);
        }

        return $this->render('hike/form.html.twig', [
            'createForm'    => $updateForm
        ]);
    }

    /**
    * Controller du bouton de suppression d'une randonnée
    * Accès : modérateur et plus
    */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_MODO')]
    #[IsCsrfTokenValid('delete-hike', '_csrf_token')] 
    public function delete(Hike $hike, EntityManagerInterface $entityManager, Request $request, LoggerInterface $logger): Response
    {
        try {
            $entityManager->remove($hike);
            $entityManager->flush();
            $this->addFlash('success', "Le pokémon a été supprimé");
        }
        catch(Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
            $logger->error($exc->getMessage());
        }

        return $this->redirectToRoute('app_hike_index');
    }

    /**
    * Controller du bouton d'ajout en favoris d'une randonnée
    */
    #[Route('/{id<\d+>}/favorite', name: 'favorite')]
    #[IsGranted('ROLE_USER')]
    public function toggleFavorite(Hike $hike, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('L\'utilisateur n\'est pas du bon type.');
        }
        
        if ($user->getFavourite()->contains($hike)) {
            $user->removeFavourite($hike);
            $this->addFlash('success', 'La randonnée a été retirée de vos favoris.');
        } else {
            $user->addFavourite($hike);
            $this->addFlash('success', 'La randonnée a été ajoutée à vos favoris !');
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_hike_show', [
            'id' => $hike->getId()
        ]);
    }

    /**
    * Controller du bouton de "marquer comme fait"
    */
    #[Route('/hike/{id}/done', name: 'done')]
    #[IsGranted('ROLE_USER')]
    public function markAsDone(Hike $hike, Request $request, EntityManagerInterface $em): Response
    {   
        $hikeDone = new HikeDone();
        $hikeDone->setHike($hike);
        $hikeDone->setHiker($this->getUser());

        $form = $this->createForm(HikeDoneFormType::class, $hikeDone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($hikeDone);
            $em->flush();

            $this->addFlash('success', 'Félicitations pour cette randonnée !');
            return $this->redirectToRoute('app_hike_show', ['id' => $hike->getId()]);
        }

        return $this->render('hike/show.html.twig', [
            'hike' => $hike,
            'doneForm' => $form,
        ]);
    }

}

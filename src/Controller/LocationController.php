<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationCreateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/location', name: 'app_location_')]
final class LocationController extends AbstractController
{
    
    public function index(): Response
    {
        return $this->render('location/index.html.twig', [
            'controller_name' => 'LocationController',
        ]);
    }

    /**
    * Controller pour la création d'une localisation sur la page admin
    */
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $objNewLocation = new Location();

        $createForm = $this->createForm(LocationCreateFormType::class, $objNewLocation);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $entityManager->persist($objNewLocation);
            $entityManager->flush();

            $this->addFlash('success', "La localisation a bien été ajoutée");

        }

        return $this->render('hike/form.html.twig', [
            'createForm'    => $createForm
        ]);
    }

    /**
    * Controller pour la modification d'une localisation sur la page admin
    */
    #[Route('/{id<\d+>}/update', name: 'update')] 
    #[IsGranted('ROLE_ADMIN')]
    public function update(Location $location, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $updateForm = $this->createForm(LocationCreateFormType::class, $location);
        $updateForm->handleRequest($request);
        if($updateForm->isSubmitted() && $updateForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "La localisation a bien été modifiée");

            return $this->redirectToRoute('app_hike_show', [
                'id' => $location->getId()
            ]);
        }

        return $this->render('hike/form.html.twig', [
            'createForm'    => $updateForm
        ]);
    }
}

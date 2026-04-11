<?php

namespace App\Controller;

use App\Entity\Hike;
use App\Form\HikeCreateFormType;
use App\Repository\HikeRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/hike', name: 'app_hike_')]
final class HikeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(HikeRepository $hikeRepository): Response
    {
        $arrHike = $hikeRepository->findAll();

        return $this->render('hike/index.html.twig', [
            'hikeList'          => $arrHike,
        ]);
    }

     #[Route('/{id<\d+>}', name: 'show')]
    public function show(Hike $hike): Response
    {


        return $this->render('hike/show.html.twig', [
            'hike'       => $hike
        ]);
    }

    #[Route('/create', name: 'create')]
    //#[IsGranted('ROLE_USER')] // A CHANGER QUAND J'AI CREE LES ROLES
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $objNewHike = new Hike();

        $createForm = $this->createForm(HikeCreateFormType::class, $objNewHike);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {


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

     #[Route('/{id<\d+>}/update', name: 'update')] 
    //#[IsGranted('ROLE_USER')] //A CHANGER QUAND J'AI CREE LES ROLES
    public function update(Hike $hike, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $updateForm = $this->createForm(HikeCreateFormType::class, $hike);
        $updateForm->handleRequest($request);
        if($updateForm->isSubmitted() && $updateForm->isValid()) {

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

    //Faudra faire un delete quand même

    #[Route('/{id<\d+>}/favorite', name: 'favorite')]
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

}

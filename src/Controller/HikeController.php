<?php

namespace App\Controller;

use App\Entity\Hike;
use App\Repository\HikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HikeController extends AbstractController
{
    #[Route('/hike', name: 'app_hike')]
    public function index(HikeRepository $hikeRepository): Response
    {
        $arrHike = $hikeRepository->findAll();

        return $this->render('hike/index.html.twig', [
            'controller_name'   => 'HikeController',
            'hikeList'          => '$arrHike'
        ]);
    }

     #[Route('/hike/{id<\d+>}', name: 'app_hike_show')]
    public function show(Hike $hike): Response
    {


        return $this->render('hike/show.html.twig', [
            'hike'       => $hike
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\HikeDone;
use App\Entity\User;
use App\Form\UserInfoFormType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user_')]
final class UserController extends AbstractController
{   
    /**
    * Controller et affichage du dashboard admin
    * Accès : admin
    */
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $arrUsers = $userRepository->findBy(
                                    [],                  
                                    ['name' => 'ASC']
        );

        return $this->render('user/index.html.twig', [
            'controller_name'   => 'UserController',
            'userList'          => $arrUsers
        ]);
    }

    /**
    * Controller et affichage de la page d'un utilisateur
    */
    #[Route('/{id<\d+>}', name: 'show')]
    public function show(User $user): Response
    {

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
    * Controller et affichage de la page de modification d'un utilisateur
    * Accès : utilisateur concerné et admin
    */
    #[Route('/{id<\d+>}/update', name: 'update')]
    #[IsGranted('PROFILE_EDIT', subject: 'user')]
    public function update(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {

        $userForm = $this->createForm(UserInfoFormType::class, $user);
        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid()) {
            $imageFile = $userForm->get('image')->getData();
            if ($imageFile) {
                if ($user->getImage()) {
                    $fileUploader->remove($user->getImage());
                }
            $newFilename = $fileUploader->upload($imageFile);
            $user->setImage($newFilename);
            }

            $entityManager->flush();
            $this->addFlash('success', "Votre profil a été mis à jour.");

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $userForm
        ]);
    }
}

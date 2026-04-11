<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserInfoFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'app_user_')]
final class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        $arrUsers = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'controller_name'   => 'UserController',
            'userList'          => $arrUsers
        ]);
    }

    #[Route('/{id<\d+>}', name: 'show')]
    public function show(User $user): Response
    {

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/{id<\d+>/update}', name: 'update')]
    public function update(User $user, Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager): Response
    {
        $userForm = $this->createForm(UserInfoFormType::class, $user);

        $userForm->handleRequest($request);
        
        if($userForm->isSubmitted() && $userForm->isValid()) {

            /** @var string $plainPassword */
            $plainPassword = $userForm->get('plainPassword')->getData();
            if($plainPassword) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }

            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été modifié");

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/form.html.twig', [
            'userForm' => $userForm
        ]);
    }
}

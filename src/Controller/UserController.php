<?php

namespace App\Controller;
use App\Entity\Location;
use App\Entity\User;
use App\Form\LocationCreateFormType;
use App\Form\UserInfoFormType;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
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
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, LocationRepository $locationRepository): Response
    {
        $arrUsers = $userRepository->findBy(
                                    [],                  
                                    ['name' => 'ASC']
        );

        $objNewLocation = new Location();
        $locationCreateFormType = $this->createForm(LocationCreateFormType::class, $objNewLocation);
        $locationCreateFormType->handleRequest($request);
        if($locationCreateFormType->isSubmitted() && $locationCreateFormType->isValid()) {
            $entityManager->persist($objNewLocation);
            $entityManager->flush();
            $this->addFlash('success', "La localisation a bien été ajoutée");

        }

        return $this->render('user/index.html.twig', [
            'userList'          => $arrUsers,
            'locationCreateFormType'        => $locationCreateFormType
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
    * Accès : utilisateur concerné
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

    /**
    * Controller du bouton de suppression d'un utilisateur
    * Accès : Administrateur
    */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('delete_user' . $user->getId(), $submittedToken)) {
            $this->addFlash('danger', 'Jeton de sécurité invalide.');
            return $this->redirectToRoute('app_user_index');
        }

        try {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', "L'utilisateur a bien été supprimé.");
        }
        catch(Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Cet utilisateur a peut-être des randonnées liées.");
            $logger->error($exc->getMessage());
        }

        return $this->redirectToRoute('app_user_index');
    }
    
    /**
    * Controller du bouton de changement de role d'un utilisateur
    * Accès : Administrateur
    */
    #[Route('/{id<\d+>}/change_role', name: 'change_role', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function changeRole(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('change_role' . $user->getId(), $submittedToken)) {
            $this->addFlash('danger', 'Jeton de sécurité invalide.');
            return $this->redirectToRoute('app_user_index');
        }
        $newRole = $request->request->get('new_role');
        $allowedRoles = ['ROLE_USER', 'ROLE_MODO', 'ROLE_ADMIN'];
        if (in_array($newRole, $allowedRoles)) {
            $user->setRoles([$newRole]); 
            $entityManager->flush();
            $this->addFlash('success', "Le rôle de l'utilisateur a été mis à jour.");
        } else {
            $this->addFlash('danger', 'Rôle invalide.');
        }
        return $this->redirectToRoute('app_user_index');
    }
}

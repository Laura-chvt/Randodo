<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Hike;
use App\Form\CommentCreateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comment', name: 'app_comment_')]
final class CommentController extends AbstractController
{

    /**
    * Fonction de création d'un commentaire
    * Accès : utilisateur connecté
    */
    #[Route('/create/{id<\d+>}', name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(Hike $hike, Request $request, EntityManagerInterface $entityManager): Response
    {

        $objNewComment = new Comment();
        $createForm = $this->createForm(CommentCreateFormType::class, $objNewComment);
        $createForm->handleRequest($request);

        if($createForm->isSubmitted() && $createForm->isValid()) {
            $objNewComment->setHike($hike);
            $objNewComment->setUserComment($this->getUser());
            $objNewComment->setDate(new \DateTime());

            $entityManager->persist($objNewComment);
            $entityManager->flush();

            $this->addFlash('success', "Votre avis a été publié !");
        }

        return $this->redirectToRoute('app_hike_show', [
            'id' => $hike->getId(),
        '_fragment' => 'comment-section'
        ]);
    
    }


    /**
    * fonction de mise à jour du commentaire
    * Accès : Utilisateur qui a créé le commentaire
    */
    #[Route('/{id<\d+>}/update', name: 'update')] 
    #[IsGranted('EDIT', subject: 'comment')]
    public function update(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        $updateForm = $this->createForm(CommentCreateFormType::class, $comment);
        $updateForm->handleRequest($request);
        
        if($updateForm->isSubmitted() && $updateForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Votre avis a bien été modifié");
            return $this->redirectToRoute('app_hike_show', [
                'id' => $comment->getHike()->getId(),
                '_fragment' => 'comment-section'
            ]);
        }
        return $this->render('comment/update.html.twig', [
            'updateForm' => $updateForm,
            'hike' => $comment->getHike()
        ]);
    }

    /**
    * Fonction de suppression du commentaire
    * Accès : le créateur, le modérateur et l'adiminstrateur
    */
    #[Route('/{id<\d+>}/delete', name: 'delete', methods: ['POST'])]
    #[IsGranted('DELETE', subject: 'comment')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', "Le commentaire a été supprimé");
        }
        catch(Exception $exc) {
            $this->addFlash('danger', "Une erreur est survenue. Réessayez");
            $logger->error($exc->getMessage());
        }

        return $this->redirectToRoute('app_hike_show', [
            'id' => $comment->getId()
        ]);
    }
}

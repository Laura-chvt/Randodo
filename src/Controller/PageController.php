<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class PageController extends AbstractController
{   
    /**
    * Controller et affichage de la page "qui sommes-nous" et formulaire de contact
    */
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $contactFormData = $form->getData();
            $email = (new Email())
                ->from($contactFormData['email'])
                ->to('laura.chvt@laura.chevillet.servd16161.odns.fr')
                ->subject('Nouveau message de ' . $contactFormData['name'])
                ->text($contactFormData['message']);
            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons très vite !');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('page/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}

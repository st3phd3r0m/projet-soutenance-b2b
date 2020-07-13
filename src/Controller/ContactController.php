<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            // Ici nous enverrons le mail
            $message = (new \Swift_Message('Nouveau Contact'))
              // On attribue l'expediteur
              ->setFrom($contact['email'])

              // On attribue le destinataire
              ->setTo('votre@adresse.fr')

              // On crée le message avec la vue twig
              ->setBody(
                  $this->renderView(
                      'emails/contact.html.twig', compact('contact')
                  ),
                  'text/html'
              )
            ;
              // On envoie le message
              $mailer->send($message);
              $this->addFlash('message', 'Votre message a bien été envoyé !');  
        }
        return $this->render('contact/index.html.twig', [
            'ContactForm' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     */
    public function index(): Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }

    
    /**
    *@Route("/sendEmail"), name="send_email"
    */
    public function sendEmail(MailerInterface $mailer){
        $email=(new Email())
        ->from('info@webtronblue.com')
        ->to('marcus.kimba@webtronblue.com')
        ->subject('Mon premier email')
        ->text('Salut Marcus , bravo pour le progres ')
        ->html('<h1>jambo kwa wote </h1>');

        $mailer->send($email);
        $this->addFlash('success', 'Opération réussie. ');
        $this->generateUrl('accueil');
    }
}

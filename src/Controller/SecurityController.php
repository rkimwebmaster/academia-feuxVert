<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        if ($this->getUser()) {
            if($this->isGranted('ROLE_FINALISTE')){
                if($this->getUser()->getFinaliste()()===null){
                    return $this->redirectToRoute('finaliste_new');
                }
                    if($this->getUser()->getFinaliste()->getFiche()===null){
                        return $this->redirectToRoute('fiche_new');
                }
            } elseif($this->isGranted('ROLE_ENSEIGNANT')){
                if($this->getUser()->getEnseignant()===null){
                    return $this->redirectToRoute('enseignant_new');
                } 
            }
            $this->addFlash('success', 'Vous êtes déjà connécté. ');
            return $this->redirectToRoute('accueil');
        }



        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

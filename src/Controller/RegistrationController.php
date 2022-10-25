<?php

namespace App\Controller;

use App\Entity\Faculte;
use App\Entity\Promotion;
use App\Entity\User;
use App\Form\RegistrationEnseignantFormType;
use App\Form\RegistrationFacFormType;
use App\Form\RegistrationFinalisteFormType;
use App\Form\RegistrationFormAdminType;
use App\Form\RegistrationFormType;
use App\Form\RegistrationSAFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
      *@IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        dd('ce scripts est interdit');

        $checkfaculte = $this->getDoctrine()->getRepository(Faculte::class)->findAll();
        if (sizeof($checkfaculte) == 0) {
            $this->addFlash('warning', 'Aucune faculté/section dans la base. Créez-en une en premier. ');
            return $this->redirectToRoute('faculte_new');
        }
        $user = new User();

        if ($this->isGranted('ROLE_ADMIN')) {
            //user admin des facultés 
            $form = $this->createForm(RegistrationFormType::class, $user);
        } else {
            $form = $this->createForm(RegistrationFormAdminType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /// si l'utilisateur n a pas de faculté il prend celui de son createur 
            if ($user->getFaculte() == null) {
                if ($faculte = $this->getUser()->getFaculte()) {
                    $user->setFaculte($faculte);
                }
            }


            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $role[] = $form->get('role')->getData();
            $user->setRoles($role);
            //dd($user->getRoles());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Opération réussie. L\'utilisateur ' . $user . ' a été créée ');

            return $this->redirectToRoute('app_register');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('registration/registerFaculte.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registerEnseignant", name="app_register_enseignant")
     *@IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function registerEnseignant(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $checkfaculte = $this->getDoctrine()->getRepository(Faculte::class)->findAll();
        if (sizeof($checkfaculte) == 0) {
            $this->addFlash('warning', 'Aucune faculté/section dans la base. Créez-en une en premier. ');
            return $this->redirectToRoute('faculte_new');
        }
        $user = new User();

        if ($this->isGranted('ROLE_ADMIN')) {
            //user admin des facultés 
            $form = $this->createForm(RegistrationEnseignantFormType::class, $user);
        } else {
            $form = $this->createForm(RegistrationFormAdminType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $password=$form->get('plainPassword')->getData();
            $email=$form->get('email')->getData();
            $username=$form->get('username')->getData();
            if($password==$email){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et mail identique. ');     
                return $this->redirectToRoute('app_register_enseignant');
            }

            if($password==$username){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et nom d\'utilisateur identique. ');     
                return $this->redirectToRoute('app_register_enseignant');
            }

            /// si l'utilisateur n a pas de faculté il prend celui de son createur 
            if ($user->getFaculte() == null) {
                if ($faculte = $this->getUser()->getFaculte()) {
                    $user->setFaculte($faculte);
                }
            }


            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // $role[]=$form->get('role')->getData();
            // $user->setRoles($role);
            //dd($user->getRoles());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Opération réussie. L\'utilisateur ' . $user . ' a été créée ');

            return $this->redirectToRoute('app_register_enseignant');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('registration/registerEnseignant.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
        return $this->render('registration/registerEnseignant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registerFinaliste", name="app_register_finaliste")
      *@IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function registerFinaliste(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {


        $checkfaculte = $this->getDoctrine()->getRepository(Faculte::class)->findAll();
        if (sizeof($checkfaculte) == 0) {
            $this->addFlash('warning', 'Aucune faculté/section dans la base. Créez-en une en premier. ');
            return $this->redirectToRoute('faculte_new');
        }

        $checkPromotion = $this->getDoctrine()->getRepository(Promotion::class)->findOneBy([]);
        if ($checkPromotion == null) {
            $this->addFlash('warning', 'Aucune promotion dans la base. Créez-en une en premier. ');
            return $this->redirectToRoute('promotion_new');
        }

        $user = new User();

        if ($this->isGranted('ROLE_ADMIN')) {
            //user admin des facultés 
            $form = $this->createForm(RegistrationFinalisteFormType::class, $user);
        } else {
            $form = $this->createForm(RegistrationFormAdminType::class, $user);
        }


        $form->handleRequest($request);
        // dd('salut');
        $pass = $form->get('plainPassword')->getData();


        if ($form->isSubmitted() && $form->isValid()) {

            
            $password=$form->get('plainPassword')->getData();
            $email=$form->get('email')->getData();
            $username=$form->get('username')->getData();
            if($password==$email){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et mail identique. ');     
                return $this->redirectToRoute('app_register_finaliste');
            }

            if($password==$username){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et nom d\'utilisateur identique. ');     
                return $this->redirectToRoute('app_register_finaliste');
            }

            $checkUsername = $this->getDoctrine()->getRepository(User::class)->findBy(['username' => $user->getUsername()]);
            $checkEmail = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $user->getEmail()]);

            if ($checkUsername) {
                $this->addFlash('warning', 'Le nom utilisateur'.$checkUsername.'est déjà utilisé. ');
                return $this->redirectToRoute('app_register_finaliste');
            }
            if ($checkEmail) {
                $this->addFlash('warning', 'Le mail est déjà utilisé. ');
                return $this->redirectToRoute('app_register_finaliste');
            }
            /// si l'utilisateur n a pas de faculté il prend celui de son createur 
            if ($user->getFaculte() == null) {
                if ($faculte = $this->getUser()->getFaculte()) {
                    $user->setFaculte($faculte);
                }
            }


            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // $role[]=$form->get('role')->getData();
            // $user->setRoles($role);
            //dd($user->getRoles());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Opération réussie. L\'utilisateur ' . $user . ' a été créée ');

            return $this->redirectToRoute('app_register_finaliste');
        }
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('registration/registerFinaliste.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
        return $this->render('registration/registerFinaliste.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/registerAdmin", name="app_register_admin")
      *@IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password=$form->get('plainPassword')->getData();
            $email=$form->get('email')->getData();
            $username=$form->get('username')->getData();
            if($password==$email){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et mail identique. ');     
                return $this->redirectToRoute('app_register_admin');
            }

            if($password==$username){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et nom d\'utilisateur identique. ');     
                return $this->redirectToRoute('app_register_admin');
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();

            $role[] = $form->get('role')->getData();
            $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Opération réussie. L\'utilisateur ' . $user . ' a été créée ');

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registerSA", name="app_register_sa")
     */
    public function registerSA(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationSAFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password=$form->get('plainPassword')->getData();
            $email=$form->get('email')->getData();
            $username=$form->get('username')->getData();
            if($password==$email){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et mail identique. ');     
                return $this->redirectToRoute('app_register_sa');
            }

            if($password==$username){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et nom d\'utilisateur identique. ');     
                return $this->redirectToRoute('app_register_sa');
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();

            // $role[]=$form->get('role')->getData();
            // $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Opération réussie. L\'utilisateur ' . $user . ' a été créée ');

            return $this->redirectToRoute('app_register_sa');
        }

        return $this->render('registration/registerSA.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registerFac", name="app_register_fac")
      *@IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function registerFac(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $checkfaculte = $this->getDoctrine()->getRepository(Faculte::class)->findAll();
        if (sizeof($checkfaculte) == 0) {
            $this->addFlash('warning', 'Aucune faculté/section dans la base. Créez-en une en premier. ');
            return $this->redirectToRoute('faculte_new');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFacFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password=$form->get('plainPassword')->getData();
            $email=$form->get('email')->getData();
            $username=$form->get('username')->getData();
            if($password==$email){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et mail identique. ');     
                return $this->redirectToRoute('app_register_fac');
            }

            if($password==$username){
                $this->addFlash('warning', 'Attention, Problème de scurité. L\'utilisateur ' . $user . ' a le mot de passe et nom d\'utilisateur identique. ');     
                return $this->redirectToRoute('app_register_fac');
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Opération réussie. L\'utilisateur ' . $user . ' a été créée ');

            return $this->redirectToRoute('app_register_fac');
        }

        return $this->render('registration/registerFac.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

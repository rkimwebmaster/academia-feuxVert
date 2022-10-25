<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserChangePasswordAdminType;
use App\Form\UserChangePasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $users = $userRepository->findBy(['faculte' => $this->getUser()->getFaculte()],['username'=>'ASC']);
            return $this->render('user/index.html.twig', [
                'users' => $users,
            ]);
        }
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // /**
    //  * @Route("/new", name="user_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //         $this->addFlash('success', 'Opération réussie . ');

    //         return $this->redirectToRoute('user_index');
    //     }

    //     return $this->render('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="profile", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie . ');

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/changePass", name="user_change_pass", methods={"GET","POST"})
     */
    public function changePass(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ancienMotDePasse = $form->get('formerPassword')->getData();

            $checkUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
            $formerPass = $checkUser->getPassword();

            // dd($formerPass.'--------'.$hashFormerPassword);

            if ($passwordEncoder->isPasswordValid($user, $ancienMotDePasse)) {
                // dd('bamba');
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Opération réussie. Vous avez changer votre mot de passe. ');
                return $this->redirectToRoute('profile',['id'=>$this->getUser()->getId()]);

            } else {
                $this->addFlash('warning', 'L\'ancien mot de passe saisie n\'est pas correct réessayez. ');
                return $this->redirectToRoute('profile',['id'=>$this->getUser()->getId()]);
            }
            // if($hashFormerPassword===$formerPass){
            //     dd('bamba');
            // }
            dd($formerPass);

            // encode the plain password


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('accueil');
        }

        return $this->render('user/changePassword.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/changePassAdmin", name="user_change_pass_admin", methods={"GET","POST"})
     */
    public function changePassAdmin(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $user;
        $form = $this->createForm(UserChangePasswordAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {            
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. Vous avez changer le mot de passe de '.$user.'.');
            return $this->redirectToRoute('user_index');
            
        }

        return $this->render('user/changePasswordAdmin.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }



    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($finaliste = $user->getFinaliste) {
                $entityManager->remove($finaliste);
            } elseif ($directeur = $user->getEnseignant()) {
                $entityManager->remove($directeur);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie . ');
        }

        return $this->redirectToRoute('user_index');
    }
}

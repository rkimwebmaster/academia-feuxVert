<?php

namespace App\Controller;

use App\Entity\Finaliste;
use App\Entity\Promotion;
use App\Entity\User;
use App\Form\FinalisteType;
use App\Repository\FinalisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\Session;

/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/finaliste")
 */
class FinalisteController extends AbstractController
{
    /**
     * @Route(".php/", name="finaliste_index", methods={"GET"})
     */
    public function index(FinalisteRepository $finalisteRepository): Response
    {
        $finalistes = $finalisteRepository->findAll();
        if(sizeof($finalistes)==0){
            $this->addFlash('warning', 'Aucun finaliste dans le système. ');

        }
        return $this->render('finaliste/index.html.twig', [
            'finalistes' => $finalistes,
        ]);
    }

    /**
     * @Route("/new/{id}/", name="finaliste_new", methods={"GET","POST"})
     */
    public function new(Request $request, User $user=null): Response
    {
        if($user==null){
            $user=$this->getUser();
        }
        if($user->getFinaliste()){
            $this->addFlash('warning', 'Utilisateur déja parfait. ');
            return $this->redirectToRoute('fiche_new');

        }
        $promotion=$this->getDoctrine()->getRepository(Promotion::class)->findOneBy([]);
        if($promotion===null){
            $this->addFlash('danger', 'Aucune promotion dans le système, contactez l\'admin avant de continuer. ');
            return $this->redirectToRoute('accueil');
        }

        $finaliste = new Finaliste();
        //gestion du compteur dans les sessions 
        $session = new Session();
        $session->start();
        
        // set and get session attributes
        $session->set('iduser', $this->getUser()->getId());

        $finalistes=$this->getDoctrine()->getRepository(Finaliste::class)->findAll();

        if(sizeof($finalistes)==0){
            $session->set('compteur',0);
        }else{
            $session->set('compteur', sizeof($finalistes));
        }

        ////////////////////


        $form = $this->createForm(FinalisteType::class, $finaliste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user=$this->getUser();
            $user->setFinaliste($finaliste);
            $entityManager->persist($user);
            $entityManager->persist($finaliste);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
            if($this->isGranted('ROLE_ADMIN')){

                return $this->redirectToRoute('user_index');
            }


            if($this->isGranted('ROLE_FINALISTE')){
                $this->addFlash('success', 'Opération réussie, completez votre fiche. ');
                return $this->redirectToRoute('fiche_new');

            }else{
                $this->addFlash('success', 'Opération réussie. ');
            }

            return $this->redirectToRoute('finaliste_index');
        }

        return $this->render('finaliste/new.html.twig', [
            'finaliste' => $finaliste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="finaliste_show", methods={"GET"})
     */
    public function show(Finaliste $finaliste): Response
    {
        return $this->render('finaliste/show.html.twig', [
            'finaliste' => $finaliste,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="finaliste_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Finaliste $finaliste): Response
    {
        $form = $this->createForm(FinalisteType::class, $finaliste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('finaliste_index');
        }

        return $this->render('finaliste/edit.html.twig', [
            'finaliste' => $finaliste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="finaliste_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Finaliste $finaliste): Response
    {
        if ($this->isCsrfTokenValid('delete'.$finaliste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($finaliste);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

        }

        return $this->redirectToRoute('finaliste_index');
    }
}

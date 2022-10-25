<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\User;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/enseignant")
 */
class EnseignantController extends AbstractController
{
    /**
     * @Route("/", name="enseignant_index", methods={"GET"})
     */
    public function index(EnseignantRepository $enseignantRepository): Response
    {
        $enseignants = $enseignantRepository->findAll();
        if(sizeof($enseignants)==0){
            $this->addFlash('warning', 'Aucun enseignant dans le système. ');

        }
        return $this->render('enseignant/index.html.twig', [
            'enseignants' => $enseignantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}/", name="enseignant_new", methods={"GET","POST"})
     */
    public function new(Request $request, User $user=null): Response
    {
        if($user==null){
            $user=$this->getUser();
        }
        if($user->getEnseignant()){
            $this->addFlash('warning', 'Utilisateur déja parfait. ');
            return $this->redirectToRoute('user_index');

        }
        $enseignant = new Enseignant($user);
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($enseignant);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            if($this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('user_index');
            }
            return $this->redirectToRoute('profile',['id'=>$this->getUser()->getId()]);
        }

        return $this->render('enseignant/new.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enseignant_show", methods={"GET"})
     */
    public function show(Enseignant $enseignant): Response
    {
        return $this->render('enseignant/show.html.twig', [
            'enseignant' => $enseignant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="enseignant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Enseignant $enseignant): Response
    {
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('enseignant_index');
        }

        return $this->render('enseignant/edit.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enseignant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Enseignant $enseignant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enseignant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $user=$enseignant->getUser();
            $entityManager->persist($user);
            $entityManager->remove($enseignant);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

        }

        return $this->redirectToRoute('profile',['id'=>$user->getId()]);
    }
}

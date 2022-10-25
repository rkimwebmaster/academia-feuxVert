<?php

namespace App\Controller;

use App\Entity\AnneeAcademique;
use App\Entity\Faculte;
use App\Form\FaculteType;
use App\Repository\AnneeAcademiqueRepository;
use App\Repository\FaculteRepository;
use App\Service\AnneeCourante;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/faculte")
 */
class FaculteController extends AbstractController
{
    
    /**
     * @Route("/", name="faculte_index", methods={"GET"})
     */
    public function index(FaculteRepository $faculteRepository): Response
    {
        return $this->render('faculte/index.html.twig', [
            'facultes' => $faculteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/faculteChoix", name="faculte_choix", methods={"GET"})
     */
    public function faculteChoix(Request $request, FaculteRepository $faculteRepository): Response
    {
        if($id=$request->get('id')){
            $faculte=$this->getDoctrine()->getRepository(Faculte::class)->find($id);
            $user=$this->getUser();
            $user->setFaculte($faculte);
            // $user->setRoles(['ROLE_ADMIN']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('accueil');

        }
        return $this->render('faculte/faculteChoix.html.twig', [
            'facultes' => $faculteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="faculte_new", methods={"GET","POST"})
     */
    public function new(Request $request, FaculteRepository $faculteRepository,  AnneeAcademiqueRepository $anneeAcademiqueRepository, AnneeCourante $anneeCourante): Response
    {

        //l'année academique doit etre créer en premier 
        
        $anneeActuelle=$anneeAcademiqueRepository->findOneBy(['isCurrent'=>true]);
        if($anneeActuelle==null){
            $this->addFlash('warning', 'Aucune année courante créées. créez-en une en prmier. ');
            return $this->redirectToRoute('annee_academique_new');


        }        
        $faculte = new Faculte($anneeCourante);
        $form = $this->createForm(FaculteType::class, $faculte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($faculte);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            // if($form['resterSurCettePage']){
            //     return $this->redirectToRoute('faculte_new');
            // }
            return $this->redirectToRoute('faculte_new');
        }

        return $this->render('faculte/new.html.twig', [
            'faculte' => $faculte,
            'form' => $form->createView(),
            'facultes' => $faculteRepository->findAll(),

        ]);
    }

    /**
     * @Route("/{id}", name="faculte_show", methods={"GET"})
     */
    public function show(Faculte $faculte): Response
    {
        return $this->render('faculte/show.html.twig', [
            'faculte' => $faculte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="faculte_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Faculte $faculte): Response
    {
        $form = $this->createForm(FaculteType::class, $faculte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('faculte_index');
        }

        return $this->render('faculte/edit.html.twig', [
            'faculte' => $faculte,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="faculte_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Faculte $faculte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faculte->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($faculte);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

        }

        return $this->redirectToRoute('faculte_index');
    }
}

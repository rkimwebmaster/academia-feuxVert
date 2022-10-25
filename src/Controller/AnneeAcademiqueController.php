<?php

namespace App\Controller;

use App\Entity\AnneeAcademique;
use App\Form\AnneeAcademiqueType;
use App\Repository\AnneeAcademiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/annee/academique")
 */
class AnneeAcademiqueController extends AbstractController
{
    /**
     * @Route("/", name="annee_academique_index", methods={"GET"})
     */
    public function index(AnneeAcademiqueRepository $anneeAcademiqueRepository): Response
    {
        return $this->render('annee_academique/index.html.twig', [
            'annee_academiques' => $anneeAcademiqueRepository->findBy([],['createdAt'=>'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="annee_academique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $anneeAcademique = new AnneeAcademique();
        $form = $this->createForm(AnneeAcademiqueType::class, $anneeAcademique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //garantir qu une seule annee acedemique est a true 
            if($anneeAcademique->getIsCurrent()==true){
                
                $checkOtherCurrent=$this->getDoctrine()->getRepository(AnneeAcademique::class)->findOneBy(['isCurrent'=>true]);
                if($checkOtherCurrent){
                    $checkOtherCurrent->setIsCurrent(false);
                    $entityManager->persist($anneeAcademique);    
                }
            }else{
                $checkOtherCurrent=$this->getDoctrine()->getRepository(AnneeAcademique::class)->findOneBy(['isCurrent'=>true]);
                if($checkOtherCurrent==null){
                    $anneeAcademique->setIsCurrent(true);
                    $entityManager->persist($anneeAcademique);    
                }  
            }
            $entityManager->persist($anneeAcademique);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('annee_academique_new');
        }

        return $this->render('annee_academique/new.html.twig', [
            'annee_academique' => $anneeAcademique,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="annee_academique_show", methods={"GET"})
    //  */
    // public function show(AnneeAcademique $anneeAcademique): Response
    // {
    //     return $this->render('annee_academique/show.html.twig', [
    //         'annee_academique' => $anneeAcademique,
    //     ]);
    // }

    /**
     * @Route("/{id}/edit", name="annee_academique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AnneeAcademique $anneeAcademique): Response
    {
        $form = $this->createForm(AnneeAcademiqueType::class, $anneeAcademique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('annee_academique_index');
        }

        return $this->render('annee_academique/edit.html.twig', [
            'annee_academique' => $anneeAcademique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annee_academique_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AnneeAcademique $anneeAcademique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anneeAcademique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($anneeAcademique);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

        }

        return $this->redirectToRoute('annee_academique_index');
    }
}

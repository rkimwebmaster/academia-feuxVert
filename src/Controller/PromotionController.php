<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Entreprise;
use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/promotion")
 */
class PromotionController extends AbstractController
{
    /**
     * @Route("/", name="promotion_index", methods={"GET"})
     */
    public function index(PromotionRepository $promotionRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $faculte = $this->getUser()->getFaculte();
            $promotions = $promotionRepository->findBy(['faculte'=>$faculte],['designation'=>'asc']);
            return $this->render('promotion/index.html.twig', [
                'promotions' => $promotions,
            ]);
        }
        $promotions = $promotionRepository->findAll();
        if ($promotions == null) {
            $this->addFlash('warning', 'Aucune promotion dans le systeme, créez-en une. ');
            return $this->redirectToRoute('promotion_new');
        }

        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotions,
        ]);
    }

    /**
     * @Route("/new", name="promotion_new", methods={"GET","POST"})
     */
    public function new(Request $request, PromotionRepository $promotionRepository): Response
    {
        $departement = $this->getDoctrine()->getRepository(Departement::class)->findOneBy([]);
        if ($departement == null) {
            $this->addFlash('warning', 'Aucun département dans le systeme, créez-en un. ');
            return $this->redirectToRoute('departement_new');
        }
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        ////kiste des romotions 
        if ($this->isGranted('ROLE_ADMIN')) {
            $faculte = $this->getUser()->getFaculte();
            $promotions = $promotionRepository->findBy(['faculte'=>$faculte],['designation'=>'asc']);
        }
        $promotions = $promotionRepository->findAll();
        if ($promotions == null) {
            $this->addFlash('warning', 'Aucune promotion dans le systeme, créez-en un. ');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($promotion->getAnneeAcademique() == null) {
                $universite = $this->getDoctrine()->getRepository(Entreprise::class)->findOneBy([]);
                $promotion->setAnneeAcademique($universite->getAnneeAcademiqueCourante());
            }
            $entityManager->persist($promotion);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('promotion_new');
        }

        return $this->render('promotion/new.html.twig', [
            'promotions' => $promotions,
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_show", methods={"GET"})
     */
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="promotion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Promotion $promotion): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');
            return $this->redirectToRoute('promotion_index');
        }

        return $this->render('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Promotion $promotion): Response
    {
        if ($this->isCsrfTokenValid('delete' . $promotion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($promotion);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('promotion_index');
    }
}

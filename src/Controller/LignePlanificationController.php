<?php

namespace App\Controller;

use App\Entity\LignePlanification;
use App\Form\LignePlanification1Type;
use App\Repository\LignePlanificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/ligne/planification")
 */
class LignePlanificationController extends AbstractController
{
    /**
     * @Route("/", name="ligne_planification_index", methods={"GET"})
     */
    public function index(LignePlanificationRepository $lignePlanificationRepository): Response
    {
        return $this->render('ligne_planification/index.html.twig', [
            'ligne_planifications' => $lignePlanificationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_planification_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lignePlanification = new LignePlanification();
        $form = $this->createForm(LignePlanification1Type::class, $lignePlanification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lignePlanification);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_planification_index');
        }

        return $this->render('ligne_planification/new.html.twig', [
            'ligne_planification' => $lignePlanification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_planification_show", methods={"GET"})
     */
    public function show(LignePlanification $lignePlanification): Response
    {
        return $this->render('ligne_planification/show.html.twig', [
            'ligne_planification' => $lignePlanification,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_planification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LignePlanification $lignePlanification): Response
    {
        $form = $this->createForm(LignePlanification1Type::class, $lignePlanification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_planification_index');
        }

        return $this->render('ligne_planification/edit.html.twig', [
            'ligne_planification' => $lignePlanification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_planification_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LignePlanification $lignePlanification): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignePlanification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lignePlanification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ligne_planification_index');
    }
}

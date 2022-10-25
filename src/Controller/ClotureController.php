<?php

namespace App\Controller;

use App\Entity\Cloture;
use App\Form\ClotureType;
use App\Repository\ClotureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/cloture")
 */
class ClotureController extends AbstractController
{
    /**
     * @Route("/", name="cloture_index", methods={"GET"})
     */
    public function index(ClotureRepository $clotureRepository): Response
    {
        return $this->render('cloture/index.html.twig', [
            'clotures' => $clotureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cloture_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cloture = new Cloture();
        $form = $this->createForm(ClotureType::class, $cloture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cloture);
            $entityManager->flush();

            return $this->redirectToRoute('cloture_index');
        }

        return $this->render('cloture/new.html.twig', [
            'cloture' => $cloture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cloture_show", methods={"GET"})
     */
    public function show(Cloture $cloture): Response
    {
        return $this->render('cloture/show.html.twig', [
            'cloture' => $cloture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cloture_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cloture $cloture): Response
    {
        $form = $this->createForm(ClotureType::class, $cloture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cloture_index');
        }

        return $this->render('cloture/edit.html.twig', [
            'cloture' => $cloture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cloture_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cloture $cloture): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cloture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cloture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cloture_index');
    }
}

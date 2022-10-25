<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\PaiementDepot;
use App\Form\PaiementDepotType;
use App\Repository\FicheRepository;
use App\Repository\PaiementDepotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/paiement/depot")
 */
class PaiementDepotController extends AbstractController
{
    /**
     * @Route("/", name="paiement_depot_index", methods={"GET"})
     */
    public function index(PaiementDepotRepository $paiementDepotRepository): Response
    {
        $paiementDepots = $paiementDepotRepository->findAll();
        if(sizeof($paiementDepots)==0){
            $this->addFlash('warning', 'Aucun paiement efffectué. ');
            return $this->redirectToRoute('accueil');
        }
        return $this->render('paiement_depot/index.html.twig', [
            'paiement_depots' => $paiementDepots,
        ]);
    }

    /**
     * @Route("/paiementDepotCreation", name="paiement_depot_creation", methods={"GET"})
     */
    public function indexPaiementDepotCreation(FicheRepository $ficheRepository): Response
    {
        $fiches = $ficheRepository->findBy(['isPaiementDepot'=>false]);
        // $fiches = $ficheRepository->findBy(['isFeuxVert' => true, 'etatFiche'=> 4]);
        if(sizeof($fiches)==0){
            $this->addFlash('warning', 'Aucune fiche non en règle pour l\'instant. ');
            return $this->redirectToRoute('accueil');

        }


            return $this->render('paiement_depot/creationPaiementDepot.html.twig', [
                'fiches' => $fiches,
            ]);
    }

    /**
     * @Route("/new/{id}", name="paiement_depot_new", methods={"GET","POST"})
     */
    public function new(Request $request, Fiche $fiche): Response
    {
        $paiementDepot = new PaiementDepot($fiche);
        $paiementDepot->setUser($this->getUser());
        /////

        $entityManager = $this->getDoctrine()->getManager();

        $fiche=$paiementDepot->getFiche();
        $fiche->setIsPaiementDepot(true);
        $entityManager->persist($fiche);
        $entityManager->persist($paiementDepot);
        $entityManager->flush();
        $this->addFlash('success', 'Opération réussie. Paiement éffectué avec succès. ');

        return $this->redirectToRoute('paiement_depot_index');



        /////

        $form = $this->createForm(PaiementDepotType::class, $paiementDepot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd('salut');
            $entityManager = $this->getDoctrine()->getManager();

            $fiche=$paiementDepot->getFiche();
            $fiche->setIsPaiementDepot(true);
            $entityManager->persist($fiche);
            $entityManager->persist($paiementDepot);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie . ');

            return $this->redirectToRoute('paiement_depot_index');
        }
        dd('dehors');

        return $this->render('paiement_depot/new.html.twig', [
            'paiement_depot' => $paiementDepot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paiement_depot_show", methods={"GET"})
     */
    public function show(PaiementDepot $paiementDepot): Response
    {
        return $this->render('paiement_depot/show.html.twig', [
            'paiement_depot' => $paiementDepot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="paiement_depot_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PaiementDepot $paiementDepot): Response
    {
        $form = $this->createForm(PaiementDepotType::class, $paiementDepot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie . ');
            return $this->redirectToRoute('paiement_depot_index');
        }

        return $this->render('paiement_depot/edit.html.twig', [
            'paiement_depot' => $paiementDepot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paiement_depot_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PaiementDepot $paiementDepot): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiementDepot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paiementDepot);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie . ');

        }

        return $this->redirectToRoute('paiement_depot_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\AnneeAcademique;
use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\AnneeAcademiqueRepository;
use App\Repository\DepartementRepository;
use App\Service\CreateurPromotion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/departement")
 */
class DepartementController extends AbstractController
{

    // /**
    //  * @Route("/creerPromotion/{id}", name="departement_creer_promotion", methods={"GET"})
    //  */
    // public function creerPromotion(Departement $departement,AnneeAcademiqueRepository $anneeAcademiqueRepository, EntityManagerInterface $objectManager): Response
    // {
    //     $createurPromotion = new CreateurPromotion($objectManager, $anneeAcademiqueRepository, $departement);
    //     $createurPromotion->creerPromotion();
    //     $this->addFlash('success', 'Opération réussie. ');

    //     return $this->redirectToRoute('departement_index');   
    //  }



    /**
     * @Route("/", name="departement_index", methods={"GET"})
     */
    public function index(DepartementRepository $departementRepository): Response
    {
        $faculte=$this->getUser()->getFaculte();
        if($faculte){
            $departements = $departementRepository->findBy(['faculte'=>$faculte],['designation'=>'ASC']);
        } elseif($faculte==null){
            $departements = $departementRepository->findAll();
        }
        return $this->render('departement/index.html.twig', [
            'departements' => $departements,
        ]);
    }

    /**
     * @Route("/new", name="departement_new", methods={"GET","POST"})
     */
    public function new(Request $request, Departementrepository $departementRepository, AnneeAcademiqueRepository $anneeAcademiqueRepository): Response
    {
        if(! $this->isGranted('ROLE_ADMIN')){
            $this->addFlash('warning', 'Pour créé un département vous devez être conncté comme admin de faculté. choisir faculté. ');
            return $this->redirectToRoute('accueil');
        }
        $anneeCourante=$this->getDoctrine()->getRepository(AnneeAcademique::class)->findBy(['isCurrent'=>true]);
        if($anneeCourante==null){
            $this->addFlash('warning', 'Aucune année courante créées. créez-en une en prmier. ');
            return $this->redirectToRoute('annee_academique_new');


        }
        $departement = new Departement($anneeAcademiqueRepository);
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        $faculte=$this->getUser()->getFaculte();
        if($faculte){
            $departements = $departementRepository->findBy(['faculte'=>$faculte],['designation'=>'ASC']);
        } elseif($faculte==null){
            $departements = $departementRepository->findAll();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if($departement->getFaculte()==null){
                $departement->setFaculte($this->getUser()->getFaculte());
            }
            $entityManager->persist($departement);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('departement_new');
        }

        return $this->render('departement/new.html.twig', [
            'departements' => $departements,
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="departement_show", methods={"GET"})
     */
    public function show(Departement $departement): Response
    {
        return $this->render('departement/show.html.twig', [
            'departement' => $departement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="departement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Departement $departement): Response
    {
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('departement_index');
        }

        return $this->render('departement/edit.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="departement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Departement $departement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($departement);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');

        }

        return $this->redirectToRoute('departement_index');
    }
}

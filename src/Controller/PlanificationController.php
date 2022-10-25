<?php

namespace App\Controller;

use App\Entity\Fiche;
use App\Entity\LignePlanification;
use App\Entity\Planification;
use App\Entity\Salle;
use App\Form\PlanificationType;
use App\Repository\FicheRepository;
use App\Repository\PlanificationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/planification")
 */
class PlanificationController extends AbstractController
{
    /**
     * @Route("/", name="planification_index", methods={"GET"})
     */
    public function index(PlanificationRepository $planificationRepository): Response
    {
        $planifications = $planificationRepository->findAll();
        if(sizeof($planifications)==0){
            $this->addFlash('warning', 'Aucune planification établie présentement. ');
            return $this->redirectToRoute('accueil');

        }
        return $this->render('planification/index.html.twig', [
            'planifications' => $planifications,
        ]);
    }

    /**
     * @Route("/newDefendu", name="planification_new_defendu", methods={"GET","POST"})
     */
    public function newDefendu(Request $request, FicheRepository $ficheRepository): Response
    {

        if ($id = $request->get('id')) {

            dd($id);
            $fiche = $this->getDoctrine()->getRepository(Fiche::class)->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fiche);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. L\'étudiant a tout dit. ');

            return $this->redirectToRoute('profile', ['id' => $this->getUser()->getId()]);
        }
        return $this->render('planification/newDefendu.html.twig', [
            //faudra filtrer par faculte 
            'fiches' => $ficheRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="planification_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //check salle 
        $salle =$this->getDoctrine()->getRepository(Salle::class)->findOneBy([]);
        if(!$salle){
            $this->addFlash('warning', 'Aucune salle dans votre systeme, veuillez en créez au prealable. ');
            return $this->redirectToRoute('salle_new');
        }

        $planification = new Planification();
        $ficheFeuxVerts = $this->getDoctrine()->getRepository(Fiche::class)->findBy(['etatFiche' => 4]);
        if (sizeof($ficheFeuxVerts) == 0) {
            $this->addFlash('warning', 'Aucun étudiant finaliste avec feux vert. ');
            return $this->redirectToRoute('planification_index');
        }

        $form = $this->createForm(PlanificationType::class, $planification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $fiches=$form['fiches']->getData();
            $fiches = $form->get('fiches')->getData();
            $heureDatePlanification=$planification->getDate();
            $minutesDefense=$planification->getMinutesDefense();
            $minutesPause=$planification->getMinutesPause();

            $entityManager = $this->getDoctrine()->getManager();
            // dd();
            $secondeDatePlanification=$heureDatePlanification->getTimestamp();
            $initialisation=$secondeDatePlanification;
            $secondeDefense=$minutesDefense*60;
            $secondePause=$minutesPause*60;
            // dd($autre);
            foreach ($fiches as $fiche) {
                $lignePlannification = new LignePlanification();
                $lignePlannification->setFiche($fiche);

                // $fiche->setIsPlanifie(true);
                $fiche->setEtatFiche(5);
                $faculte=$fiche->getFinaliste()->getPromotion()->getFaculte();
                if($faculte->getNombreFicheFeuxVert()>0){ 
                    $faculte->setNombreFicheFeuxVert($faculte->getNombreFicheFeuxVert()-1);
                    $entityManager->persist($faculte);
                }

                // $fiche->setispla(5);
                $fiche->setIsFeuxVert(false);
                $fiche->setEtatFiche(5);
                $fiche->setIsPlanifiee(true);
                // a revoir ci-dessous 
                // $fiche->setDateDefense(new \DateTime());
                $entityManager->persist($fiche);
                // $lignePlannification->setHeureDebut($heureDatePlanification);
                $secondeDebutDatePlanification=$initialisation;
                $h1=new \DateTime();
                $heuredebutLigne=$h1->setTimestamp($secondeDebutDatePlanification);
                $lignePlannification->setHeureDebut($heuredebutLigne);

                $secondeFinDatePlanification=$initialisation+$secondeDefense;
                $h2=new \DateTime();
                $heureFinLigne=$h2->setTimestamp($secondeFinDatePlanification);
                $lignePlannification->setHeureFin($heureFinLigne);
                $initialisation=$secondeFinDatePlanification+$secondePause;
                $planification->addLignePlanification($lignePlannification);
                // $convertit=strtotime($heureDatePlanification) + strtotime($minutesDefense);
                //$heureDatePlanification = date_format($convertit);
            }
            // dd($planification->getLignePlanifications());
            
            $entityManager->persist($planification);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie.La planification sera notifié au finaliste concerné.');
            return $this->redirectToRoute('planification_index');
        }

        return $this->render('planification/new.html.twig', [
            'planification' => $planification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="planification_show", methods={"GET"})
     */
    public function show(Planification $planification): Response
    {
        return $this->render('planification/show.html.twig', [
            'planification' => $planification,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="planification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Planification $planification): Response
    {
        $form = $this->createForm(PlanificationType::class, $planification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. Planification mise à jour. ');

            return $this->redirectToRoute('planification_index');
        }

        return $this->render('planification/edit.html.twig', [
            'planification' => $planification,
            'form' => $form->createView(),
        ]);
    }
/**
 * @Route("/planification/{id}", name="planification_valider", methods={"GET"})
 */
    public function valider(Request $request, Planification$planification){
        dd('france');
        if($planification->getIsValidee()){
            $this->addFlash('warning','Cette planification est déjà validée.');
            $this->generateUrl('planification_index');
        }
        $planification->setIsValidee(true);
        $this->getDoctrine()->getManager()->persist($planification);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success','Opération réussie.');
        $this->generateUrl('planification_index');
    }

    /**
     * @Route("/{id}", name="planification_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Planification $planification): Response
    {
        if ($this->isCsrfTokenValid('delete' . $planification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($planification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('planification_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\DepotRepository;
use App\Repository\FicheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
*
*/
class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        $checkEntreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findOneBy([]);
        if(!$checkEntreprise){
            $this->addFlash('warning', 'Configurer avant tout votre organisation. ');
            return $this->redirectToRoute('entreprise_new');
        }
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

        /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(FicheRepository $ficheRepository,DepotRepository $depotRepository): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $fiche=null;
        if($this->isGranted('ROLE_ADMIN')){
            $faculte=$this->getUser()->getFaculte();
            $fiches=$ficheRepository->findBy(['faculte'=>$faculte]);
            return $this->render('accueil/dashboard.html.twig', [
                'fiches' => $fiches,
            ]);

        }elseif($this->isGranted('ROLE_ENSEIGNANT')){
            $faculte=$this->getUser()->getFaculte();
            $directeur = $this->getUser()->getEnseignant();
            $depots = $depotRepository->getDepotDirecteur($directeur, $ficheRepository);
            if (sizeof($depots) == 0) {
                $this->addFlash('warning', 'Aucun dépot effectué. ');
                // return $this->redirectToRoute('accueil');
            }
            $fiches=$ficheRepository->findBy(['faculte'=>$faculte,'directeurRetenu'=>$directeur]);
            return $this->render('accueil/dashboardEnseignant.html.twig', [
                'fiches' => $fiches,
                'depots' => $depots,
            ]);

        }
        elseif($this->isGranted('ROLE_FINALISTE')){
            $finaliste = $this->getUser()->getFinaliste();
            $depots = $depotRepository->getDepotEtudiant($finaliste, $ficheRepository);
            return $this->render('accueil/dashboardEtudiant.html.twig', [
                'depots' => $depots,
            ]);


        }elseif($this->isGranted('ROLE_FINANCIER')){
            $fiches=$ficheRepository->findBy([]);
            return $this->render('accueil/dashboardFinancier.html.twig', [
                'fiches' => $fiches,
            ]);


        }
        $this->addFlash('warning', 'Vous ne possedez pas de dashboard. ');
        return $this->redirectToRoute('accueil');

        
    }

}

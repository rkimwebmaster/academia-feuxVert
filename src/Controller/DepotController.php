<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Fiche;
use App\Form\DepotEnseignantType;
use App\Form\DepotEtudiantType;
use App\Form\DepotType;
use App\Repository\DepotRepository;
use App\Repository\FicheRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/depot")
 */
class DepotController extends AbstractController
{
    /**
     * @Route("/", name="depot_index", methods={"GET"})
     */
    public function index(DepotRepository $depotRepository, FicheRepository $ficheRepository): Response
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $directeur = $this->getUser()->getEnseignant();
            $depots = $depotRepository->getDepotDirecteur($directeur, $ficheRepository);
            if (sizeof($depots) == 0) {
                $this->addFlash('warning', 'Aucun dépot en attente de correction. ');
                return $this->redirectToRoute('accueil');
            }
            //remettre le compteur des depots non mus a zero 
            $directeur->setNombreNouveauDepot(0);
            $directeur->setNombreDirection(0);
            $this->getDoctrine()->getManager()->persist($directeur);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('depot/indexEnseignant.html.twig', [
                'depots' => $depots,
            ]);
        } elseif ($this->isGranted('ROLE_FINALISTE')) {
            $finaliste = $this->getUser()->getFinaliste();
            $fiche = $finaliste->getFiche();
            if (!$fiche) {
                $this->addFlash('warning', 'Vous ne possedez pas de fiche. ');
                return $this->redirectToRoute('accueil');
            }
            if (!$fiche->getIsSoumis()) {
                $this->addFlash('warning', 'Vous devez soumettre la fiche. ');
                return $this->redirectToRoute('accueil');
            }
            $depots = $depotRepository->getDepotEtudiant($finaliste, $ficheRepository);
            if (sizeof($depots)==0) {
                $this->addFlash('warning', 'Aucun dépot éffectué de votre part jusque là . ');
                return $this->redirectToRoute('accueil');
            }
            return $this->render('depot/indexEtudiant.html.twig', [
                'depots' => $depots,
            ]);
        }
        return $this->render('depot/index.html.twig', [
            'depots' => $depotRepository->findAll(),
        ]);
    }


    /**
     * @Route("/indexRecherche", name="depot_recherche_index", methods={"GET"})
     */
    public function indexRecherche(DepotRepository $depotRepository, FicheRepository $ficheRepository): Response
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $directeur = $this->getUser()->getEnseignant();
            $depots = $depotRepository->getDepotDirecteur($directeur, $ficheRepository);
            if (sizeof($depots) == 0) {
                $this->addFlash('warning', 'Aucun dépot en attente de correction. ');
                return $this->redirectToRoute('accueil');
            }
            //remettre le compteur des depots non mus a zero 
            $directeur->setNombreNouveauDepot(0);
            $directeur->setNombreDirection(0);
            $this->getDoctrine()->getManager()->persist($directeur);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('depot/rechercheDepot.html.twig', [
                'depots' => $depots,
            ]);
        } elseif ($this->isGranted('ROLE_FINALISTE')) {
            $finaliste = $this->getUser()->getFinaliste();
            $fiche = $finaliste->getFiche();
            if (!$fiche) {
                $this->addFlash('warning', 'Vous ne possedez pas de fiche. ');
                return $this->redirectToRoute('accueil');
            }
            if (!$fiche->getIsSoumis()) {
                $this->addFlash('warning', 'Vous devez soumettre la fiche. ');
                return $this->redirectToRoute('accueil');
            }
            $depots = $depotRepository->getDepotEtudiant($finaliste, $ficheRepository);
            if (sizeof($depots)==0) {
                $this->addFlash('warning', 'Aucun dépot éffectué de votre part jusque là . ');
                return $this->redirectToRoute('accueil');
            }
            return $this->render('depot/rechercheDepot.html.twig', [
                'depots' => $depots,
            ]);
        }
        return $this->render('depot/rechercheDepot.html.twig', [
            'depots' => $depotRepository->findAll(),
        ]);
    }

        /**
     * @Route("/indexNonCorrige", name="depot_index_non_corrige", methods={"GET"})
     */
    public function indexNonCorrige(DepotRepository $depotRepository, FicheRepository $ficheRepository): Response
    {
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $directeur = $this->getUser()->getEnseignant();
            $depots = $depotRepository->getDepotNonCorrigeDirecteur($directeur, $ficheRepository);
            if (sizeof($depots) == 0) {
                $this->addFlash('warning', 'Aucun dépot en attente de corection. ');
                return $this->redirectToRoute('accueil');
            }
            //remettre le compteur des depots non mus a zero 
            $directeur->setNombreNouveauDepot(0);
            $directeur->setNombreDirection(0);
            $this->getDoctrine()->getManager()->persist($directeur);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('depot/indexEnseignant.html.twig', [
                'depots' => $depots,
                'texte' => 'non-corrigés',
            ]);
        } 
        elseif ($this->isGranted('ROLE_FINALISTE')) {
            $finaliste = $this->getUser()->getFinaliste();
            $fiche = $finaliste->getFiche();
            if (!$fiche) {
                $this->addFlash('warning', 'Vous ne possedez pas de fiche. ');
                return $this->redirectToRoute('accueil');
            }
            if (!$fiche->getIsSoumis()) {
                $this->addFlash('warning', 'Vous devez soumettre la fiche. ');
                return $this->redirectToRoute('accueil');
            }
            if (!$fiche->getIsValidee()) {
                $this->addFlash('warning', 'Votre fiche est non encore validée. ');
                return $this->redirectToRoute('accueil');
            }
            $depots = $depotRepository->getDepotEtudiant($finaliste, $ficheRepository);
            //mettre a zero les nombre correction 
            $finaliste->setNombreCorrectionDirecteur(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($finaliste);
            $em->flush();

            return $this->render('depot/indexEtudiant.html.twig', [
                'depots' => $depots,
            ]);
        }
        return $this->render('depot/index.html.twig', [
            'depots' => $depotRepository->findAll(),
        ]);
    }

    /**
     * @Route("/indexEtudiant/{id}", name="depot_index_etudiant", methods={"GET"})
     */
    public function indexEtudiant(Fiche $fiche, DepotRepository $depotRepository, FicheRepository $ficheRepository): Response
    {

        if ($this->isGranted('ROLE_FINALISTE')) {
            $finaliste = $fiche->getFinaliste();
            $fiche = $finaliste->getFiche();
            if (!$fiche) {
                $this->addFlash('warning', 'Vous ne possedez pas de fiche. ');
                return $this->redirectToRoute('accueil');
            }
            if (!$fiche->getIsSoumis()) {
                $this->addFlash('warning', 'Vous devez soumettre la fiche. ');
                return $this->redirectToRoute('accueil');
            }
            if (!$fiche->getIsValidee()) {
                $this->addFlash('warning', 'Votre fiche est non encore validée. ');
                return $this->redirectToRoute('accueil');
            }
            $depots = $depotRepository->getDepotEtudiant($finaliste, $ficheRepository);
            return $this->render('depot/index.html.twig', [
                'depots' => $depots,
            ]);
        } elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            $enseignant = $this->getUser()->getEnseignant();
            $depots = $depotRepository->getDepotDirecteur($enseignant, $ficheRepository);
            if (sizeof($depots) == 0) {
                $this->addFlash('warning', 'Aucun dépot effectué. ');

                return $this->redirectToRoute('accueil');
            }
            return $this->render('depot/index.html.twig', [
                'depots' => $depots,
            ]);
        }
    }

    /**
     * @Route("/new", name="depot_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $finaliste = $this->getUser()->getFinaliste();
        $fiche = $this->getDoctrine()->getRepository(Fiche::class)->findOneBy(['finaliste' => $finaliste]);

        if ($fiche) {
            if ($fiche->getEtatFiche() == 4) {
                $this->addFlash('warning', 'Cette fiche a déjà son feux vert. ');
                return $this->redirectToRoute('fiche_show', ['id' => $fiche->getId()]);
            }
            $depot = new Depot($fiche);
        } else {
            $this->addFlash('warning', 'Preciser la fiche ou soit finaliser votre profil étudiant finaliste. ');
            return $this->redirectToRoute('accueil');
        }
        if (!$fiche->getIsValidee()) {
            // se rassurer que la fiche est déjà traité 
            $this->addFlash('warning', 'Votre fiche n\'est pas encore validée. Contactez le decanat/section ');
            return $this->redirectToRoute('accueil');
        }
        $form = $this->createForm(DepotEtudiantType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $depot->setFichier($newFilename);
            }

            //si pas de fichier envoyer un message a l utilisateur 
            if($depot->getFichier()==null){
                $this->addFlash('warning', 'vous avez fait un dépot sans fichier annéxé. ');
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depot);
            $entityManager->flush();
            if($depot->getDemandezRendezVous()){
                $this->addFlash('success', 'Opération réussie. Pour votre démande de rendez-vous, vous serez notifier à la confirmation du Directeur. ');
            }else{
                $this->addFlash('success', 'Opération réussie. Vous avez éffectué un dépot. ');
            }

            return $this->redirectToRoute('depot_index');
        }

        if($this->isGranted('ROLE_ENSEIGNANT')){
            return $this->render('depot/newEnseignant.html.twig', [
                'depot' => $depot,
                'form' => $form->createView(),
            ]);
        }
        return $this->render('depot/new.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_show", methods={"GET"})
     */
    public function show(Depot $depot): Response
    {
        return $this->render('depot/show.html.twig', [
            'depot' => $depot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="depot_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Depot $depot): Response
    {
        $form = $this->createForm(DepotType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('depot_index');
        }

        return $this->render('depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editEnseignant", name="depot_edit_enseignant", methods={"GET","POST"})
     */
    public function editEnseignant(Request $request, Depot $depot, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(DepotEnseignantType::class, $depot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $depot->setFichierCorrigeDirecteur($newFilename);
            }



            /////fin traitement fichier 
            $depot->setIsCorrige(true);
            $depot->setDateCorrection(new \DateTime());

            $finaliste=$depot->getFiche()->getFinaliste();
            $finaliste->setNombreCorrectionDirecteur($finaliste->getNombreCorrectionDirecteur()+1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($finaliste);
            $em->persist($depot);
            $em->flush();
            $this->addFlash('success', 'Opération réussie. Vous avez corrigé le travail. ');

            return $this->redirectToRoute('depot_index_non_corrige');
        }

        return $this->render('depot/edit.html.twig', [
            'depot' => $depot,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depot_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Depot $depot): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depot->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $enseignant = $depot->getFiche()->getDirecteurRetenu();
            $nbreNouveauDepot = $enseignant->getNombreNouveauDepot();
            if ($nbreNouveauDepot >= 0) {
                $enseignant->setNombreNouveauDepot(-1);
                $entityManager->persist($enseignant);
            }
            $entityManager->remove($depot);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('depot_index');
    }
}

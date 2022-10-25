<?php

namespace App\Controller;

use App\Entity\BroadcastMessage;
use App\Entity\Enseignant;
use App\Entity\Fiche;
use App\Entity\Message;
use App\Entity\Promotion;
use App\Entity\Proposition;
use App\Form\FicheTraiterType;
use App\Form\FicheType;
use App\Repository\FicheRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/fiche")
 */
class FicheController extends AbstractController
{
    /**
     * @Route("/", name="fiche_index", methods={"GET"})
     */
    public function index(Request $request, FicheRepository $ficheRepository): Response
    {
        // dd();
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $directeur = $user->getEnseignant();
            $directeur->setNombreNouveauDepot(0);
            $directeur->setNombreDirection(0);
            $this->getDoctrine()->getManager()->persist($directeur);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('fiche/index.html.twig', [
                'fiches' => $ficheRepository->findBy(['directeurRetenu' => $directeur]),
            ]);
        } elseif ($this->isGranted('ROLE_FINALISTE')) {
            $finaliste = $user->getFinaliste();
            if($this->isGranted('ROLE_FINALISTE')){
                //mettre a zero les nombre correction 
                $finaliste->setNombreCorrectionDirecteur(0);
                $em=$this->getDoctrine()->getManager();
                $em->persist($finaliste);
            }
            return $this->render('fiche/index.html.twig', [
                'fiches' => $ficheRepository->findBy(['finaliste' => $finaliste]),
            ]);
        } elseif ($this->isGranted('ROLE_ADMIN')) {


            if ($prom = $request->get('prom')) {
                // dd($prom);
                $promotion = $this->getDoctrine()->getRepository(Promotion::class)->find($prom);
                $fiches = $ficheRepository->findBy(['promotion' => $promotion]);
                if (sizeof($fiches) == 0) {
                    $this->addFlash('warning', 'Aucune fiche pour cette promotion dans la base. ');
                }

                return $this->render('fiche/index.html.twig', [
                    //faudra filtrer par faculte 
                    'fiches' => $fiches,
                    'promotion' => $promotion,
                ]);
            }
            if ($request->get('id')) {
                return $this->render('fiche/indexNotifierDefense.html.twig', [
                    //faudra filtrer par faculte 
                    'fiches' => $ficheRepository->findAll(),
                ]);
            } else {
                return $this->render('fiche/index.html.twig', [
                    //faudra filtrer par faculte 
                    'fiches' => $ficheRepository->findAll(),
                ]);
            }
        }
        return  $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/searchByNumero", name="search_fiche_by_numero", methods={"GET"})
     */
    public function searchByNumero(Request $request, FicheRepository $ficheRepository): Response
    {
        $numero = $request->get('numero');
        $fiches = $ficheRepository->getByCodeFiche($numero);
        if (!$fiches) {
            $this->addFlash('warning', 'Aucune fiche avec ce numro dans la base. ');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('fiche/index.html.twig', [
            'fiches' => $fiches,
        ]);
    }

    /**
     * @Route("/indexFeuxVert", name="fiche_index_feux_vert", methods={"GET"})
     */
    public function indexFeuxVert(FicheRepository $ficheRepository): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $directeur = $user->getEnseignant();
            $ficheFeuxverts = $ficheRepository->findBy(['directeurRetenu' => $user->getEnseignant(), 'isFeuxVert' => true]);
            if (sizeof($ficheFeuxverts) == 0) {
                $this->addFlash('warning', 'Aucune fiche avec feux vert parmi vos dirigés. ');
            }
            return $this->render('fiche/index.html.twig', [
                'fiches' => $ficheFeuxverts,
            ]);
        } else {
        }
        if ($finaliste = $user->getFinaliste()) {
            return $this->render('fiche/index.html.twig', [
                'fiches' => $ficheRepository->findBy(['finaliste' => $finaliste, 'isFeuxVert' => true]),
            ]);
        }
        return $this->render('fiche/index.html.twig', [
            'fiches' => $ficheRepository->findBy(['isFeuxVert' => true]),
        ]);
    }

    /**
     * @Route("/indexNonFeuxVert", name="fiche_index_non_feux_vert", methods={"GET"})
     */
    public function indexNonFeuxVert(FicheRepository $ficheRepository): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ENSEIGNANT')) {

            $directeur = $user->getEnseignant();

            $fiches = $ficheRepository->findBy(['directeurRetenu' => $directeur, 'isFeuxVert' => false]);
            if (!$fiches) {
                $this->addFlash('warning', 'Il n\'a aucun travail en cours de radaction. ');

                return $this->redirectToRoute('accueil');
            }
            return $this->render('fiche/index.html.twig', [
                'fiches' => $fiches,
            ]);
        } else {
        }
        if ($finaliste = $user->getFinaliste()) {
            return $this->render('fiche/index.html.twig', [
                'fiches' => $ficheRepository->findBy(['finaliste' => $finaliste, 'isFeuxVert' => true]),
            ]);
        }
        return $this->render('fiche/index.html.twig', [
            'fiches' => $ficheRepository->findBy(['isFeuxVert' => true]),
        ]);
    }

    /**
     * @Route("/ficheNonSoumises", name="fiche_index_non_soumises", methods={"GET"})
     */
    public function indexNonSoumises(FicheRepository $ficheRepository): Response
    {
        return $this->render('fiche/index.html.twig', [
            'fiches' => $ficheRepository->findBy(['isSoumis' => false]),
        ]);
    }

    /**
     * @Route("/ficheNonTraitees", name="fiche_index_non_traitees", methods={"GET"})
     */
    public function indexNonTraitees(FicheRepository $ficheRepository): Response
    {
        return $this->render('fiche/index.html.twig', [
            'fiches' => $ficheRepository->findBy(['etatFiche' => 2]),
        ]);
    }

    /**
     * @Route("/ficheTraitees", name="fiche_index_traitees", methods={"GET"})
     */
    public function indexTraitees(FicheRepository $ficheRepository): Response
    {
        return $this->render('fiche/index.html.twig', [
            'fiches' => $ficheRepository->findBy(['etatFiche' => 3]),
        ]);
    }

    /**
     * @Route("/new", name="fiche_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $directeur = $this->getDoctrine()->getRepository(Enseignant::class)->findAll();
        if (sizeof($directeur) == 0) {
            $this->addFlash('warning', 'Aucun directeur de travail dans le système. Contactez l\'administrateur. ');
            return $this->redirectToRoute('accueil');
        }
        $user = $this->getUser();
        $finaliste = $user->getFinaliste();
        //verifoer que le finaliste a deja une fiche 
        $ficheFinaliste = $this->getDoctrine()->getRepository(Fiche::class)->findOneBy(['finaliste' => $finaliste]);
        if ($ficheFinaliste) {
            $this->addFlash('warning', 'Vous avez dejà une fiche créée. ');

            return $this->redirectToRoute('fiche_show', ['id' => $ficheFinaliste->getId()]);
        }

        if ($this->isGranted('ROLE_FINALISTE')) {
            if ($finaliste == null) {
                $this->addFlash('warning', 'Vous dêvez finaliser les configurations comme finaliste. ');
                return $this->redirectToRoute('finaliste_new', ['id' => $this->getUser()->getId()]);
            }
            $fiche = new Fiche($finaliste);

            $proposition1 = new Proposition();
            $fiche->addProposition($proposition1);

            $proposition2 = new Proposition();
            $fiche->addProposition($proposition2);

            $proposition3 = new Proposition();
            $fiche->addProposition($proposition3);
        } else {
            $this->addFlash('warning', 'Vous dêvez etre connecté comme finaliste. ');
            return $this->redirectToRoute('fiche_index');
        }

        $form = $this->createForm(FicheType::class, $fiche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($fiche->getIsSoumis() == false) {
                $fiche->setEtatFiche(1);
            } else {
                $fiche->setIsSoumis(true);
                $fiche->setIsRejete(false);
                $fiche->setEtatFiche(2);
                $fiche->setDateSoumission(new \DateTime());
                $promotion = $fiche->getFinaliste()->getPromotion();
                $departement = $promotion->getDepartement();
                $faculte = $departement->getFaculte();
                $nombreFicheATraiter = $faculte->getNombreFicheATraiter();

                $faculte->setNombreFicheATraiter($nombreFicheATraiter + 1);
                $faculte->setNombreFicheSoumis($faculte->getNombreFicheSoumis() + 1);
                $entityManager->persist($faculte);
            }

            $entityManager->persist($fiche);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. Vous avez créée votre fiche, les prochaines étapes avant la redaction sont la soumission de cette fiche par VOUS ensuite vous attendrez sa validation par la faculté. ');
            return $this->redirectToRoute('fiche_show', ['id' => $fiche->getId()]);
        }

        return $this->render('fiche/new.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/print", name="fiche_print", methods={"GET"})
     */
    public function print(Fiche $fiche): Response
    {
        if (!$fiche->getIsFeuxVert()) {
            $this->addFlash('warning', 'Cette fiche doit être en mode feux vert. ');

            return $this->redirectToRoute('fiche_index');
        }
        return $this->render('fiche/printSuivi.html.twig', [
            'fiche' => $fiche,
        ]);
    }

    /**
     * @Route("/{id}/setDefendu", name="fiche_set_defendu", methods={"GET"})
     */
    public function setDefendu(Fiche $fiche): Response
    {
        if ($fiche->getIsDefendue()) {
            $this->addFlash('warning', 'Cette fiche est dejà classé comme défendue. ');
            return $this->redirectToRoute('fiche_index');
        } elseif ($fiche->getEtatFiche() == 5) {
            $fiche->setIsDefendue(true);
            $fiche->setEtatFiche(6);
            $this->getDoctrine()->getManager()->flush($fiche);
            $this->addFlash('success', 'Opération réussie. ');
            return $this->redirectToRoute('fiche_index');
        }
        return $this->render('fiche/printSuivi.html.twig', [
            'fiche' => $fiche,
        ]);
    }



    /**
     * @Route("/{id}", name="fiche_show", methods={"GET"})
     */
    public function show(Fiche $fiche): Response
    {
        if($this->isGranted('ROLE_FINALISTE')){
            //mettre a zero les nombre correction 
            $finaliste=$fiche->getFinaliste();
            $finaliste->setNombreCorrectionDirecteur(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($finaliste);
        }
        // return $this->render('fiche/show.html.twig', [
        if ($fiche->getEtatFiche() < 3) {
            return $this->render('fiche/ficheProposition.html.twig', [
                'fiche' => $fiche,
            ]);
        } elseif ($fiche->getEtatFiche() >= 3) {
            return $this->render('fiche/ficheAvancement.html.twig', [
                'fiche' => $fiche,
            ]);
        }
        return $this->render('fiche/show.html.twig', [
            'fiche' => $fiche,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fiche_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fiche $fiche): Response
    {
        $form = $this->createForm(FicheType::class, $fiche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('fiche_index');
        }

        return $this->render('fiche/edit.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/feuxVert", name="fiche_feux_vert", methods={"GET","POST"})
     */
    public function feuxVert(Request $request, Fiche $fiche): Response
    {
        if ($fiche->getIsFeuxVert()) {
            $this->addFlash('warning', 'Cette fiche est déjà en mode feux vert. ');
            return $this->redirectToRoute('fiche_index');
        }
        $em = $this->getDoctrine()->getManager();

        $faculte = $fiche->getFaculte();
        $faculte->setNombreFicheFeuxVert($faculte->getNombreFicheFeuxVert() + 1);
        $fiche->setIsFeuxVert(true);
        $fiche->setEtatFiche(4);
        $fiche->setDateFeuxVert(new \DateTime());
        $em->persist($fiche);
        $em->persist($faculte);
        $em->flush();
        $this->addFlash('success', 'Opération réussie. ');

        return $this->redirectToRoute('fiche_index');
    }

    /**
     * @Route("/{id}/soumettreFiche", name="fiche_soumettre", methods={"GET","POST"})
     */
    public function soumettre(Request $request, Fiche $fiche): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ($fiche->getIsSoumis()) {
            $this->addFlash('warning', 'Cette fiche est déjà soumise. ');

            return $this->redirectToRoute('fiche_show', ['id' => $fiche->getId()]);
        } else {
            $fiche->setIsSoumis(true);
            $fiche->setEtatFiche(2);
            $fiche->setIsRejete(false);
            $fiche->setDateSoumission(new \DateTime());
            $promotion = $fiche->getFinaliste()->getPromotion();
            $departement = $promotion->getDepartement();
            $faculte = $departement->getFaculte();
            $nombreFicheATraiter = $faculte->getNombreFicheATraiter();
            if ($nombreFicheATraiter >= 0) {
                $faculte->setNombreFicheATraiter($nombreFicheATraiter + 1);
                $entityManager->persist($faculte);
            }
        }
        $entityManager->flush();
        $this->addFlash('success', 'Opération réussie. Veuillez patienter le temps que l\'administration traite et valide votre sujet. ');

        return $this->redirectToRoute('fiche_index');
    }

    /**
     * @Route("/{id}/traiterFiche", name="fiche_traiter", methods={"GET","POST"})
     */
    public function traiterFiche(Request $request, Fiche $fiche): Response
    {
        $form = $this->createForm(FicheTraiterType::class, $fiche);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $checkRejet=$request->get('rejeter');
            if($checkRejet=='rejeter'){
                //faut ajouter la  notofication de refus a l etuddiant 

                $fiche->setIsRejete(true);
                $fiche->setEtatFiche(1);
                $em=$this->getDoctrine()->getManager();
                ///créer et envoyez le message en observation 
                $broadcastMessage= new BroadcastMessage($this->getUser());
                $broadcastMessage->setTitre('VOTRE PROPOSITION A ETE REJETE');
                $broadcastMessage->setGroupeDestinataire($fiche->getFinaliste());
                $broadcastMessage->setContenu($fiche->getObservation());
                $message=new Message($broadcastMessage);
                $message->setUserReceiver($fiche->getFinaliste()->getUser());

                $em->persist($message);
                $em->persist($broadcastMessage);
                //fin broad cast message 
                $em->persist($fiche);
                $em->flush();
                $this->addFlash('warning', 'Une notification sera envoyé au finaliste lui indiquant le refus de sujet.');
                return $this->redirectToRoute('fiche_index_non_traitees');
                exit();
            }
            $directeur = $fiche->getDirecteurRetenu();
            if ($directeur->getUser() == null) {
                $this->addFlash('warning', 'Le directeur choisis n\'est pas reconnu comme uer du système');
                return $this->redirectToRoute('fiche_index');
            }
            $codireceteur = $fiche->getCodirecteur();

            $directeur->setNombreDirection($directeur->getNombreDirection() + 1);
            if ($codireceteur) {
                $codireceteur->setNombreDirection($codireceteur->getNombreDirection() + 1);
                $this->getDoctrine()->getManager()->persist($codireceteur);
            }

            $this->getDoctrine()->getManager()->persist($directeur);
            if ($fiche->getIsValidee()) {
                $this->addFlash('warning', 'Cette est déjà validée. ');
                return $this->redirectToRoute('fiche_index_non_traitees');
            }
            if ($fiche->getIsSoumis()) {

                //mise a jour de la faculte 

                $faculte = $fiche->getFaculte();
                if ($faculte->getNombreFicheATraiter() >= 0) {
                    $faculte->setNombreFicheATraiter($faculte->getNombreFicheATraiter() - 1);
                    $faculte->setNombreFicheSoumis($faculte->getNombreFicheSoumis() - 1);
                }
                $fiche->setIsValidee(true);
                $fiche->setEtatFiche(3);
                $fiche->setDateValidation(new \DateTime());

                $em=$this->getDoctrine()->getManager();
                ///créer et envoyez le message en observation 
                $broadcastMessage= new BroadcastMessage($this->getUser());
                $broadcastMessage->setTitre('VOTRE SUJET A ETE VALIDE');
                $broadcastMessage->setGroupeDestinataire($fiche->getFinaliste());
                $broadcastMessage->setContenu($fiche->getObservation());
                $message=new Message($broadcastMessage);
                $message->setUserReceiver($fiche->getFinaliste()->getUser());

                $em->persist($message);
                $em->persist($broadcastMessage);
                //fin broad cast message 
                $em->persist($faculte);
                $em->persist($fiche);
                $em->flush();
            } else {
                $this->addFlash('warning', 'Cette fiche  n\'est pas encore soumise. ');

                return $this->redirectToRoute('fiche_index');
            }



            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. Fiche validée. L\'étudiant peut entammer la redaction.');

            return $this->redirectToRoute('fiche_index');
        }

        return $this->render('fiche/edit.html.twig', [
            'fiche' => $fiche,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fiche_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Fiche $fiche): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fiche->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fiche);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. ');
        }

        return $this->redirectToRoute('fiche_index');
    }
}

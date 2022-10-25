<?php

namespace App\Controller;

use App\Entity\AnneeAcademique;
use App\Entity\Entreprise;
use App\Entity\User;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/entreprise")
 */
class EntrepriseController extends AbstractController
{
    /**
     * @Route("/", name="entreprise_index", methods={"GET"})
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $uneEntreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findOneBy([]);
        if($uneEntreprise){

            // $this->addFlash('success', 'Entreprise déja existante. ');
            return $this->redirectToRoute('entreprise_show',['id'=>$uneEntreprise->getId()]);
            $entreprise=$uneEntreprise;
        }

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entrepriseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="entreprise_new", methods={"GET","POST"})
     */
    public function new(Request $request, SessionInterface $sessionInterface, SluggerInterface $slugger): Response
    {
        // dd('salut');
        $uneEntreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findOneBy([]);
        if($uneEntreprise){

            $this->addFlash('success', 'Entreprise déja existante. ');
            return $this->redirectToRoute('entreprise_show',['id'=>$uneEntreprise->getId()]);
            $entreprise=$uneEntreprise;
        }else{
            $entreprise = new Entreprise();
        }
        $sessionInterface->set('entreprise', $entreprise);
        $form = $this->createForm(EntrepriseType::class, $entreprise);
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
                            $entreprise->setLogo($newFilename);
                        }


            $entityManager = $this->getDoctrine()->getManager();

            $checkAnneCourante=$this->getDoctrine()->getRepository(AnneeAcademique::class)->findOneBy(['isCurrent'=>true]);
            if($checkAnneCourante){
                $entreprise->setAnneeAcademiqueCourante($checkAnneCourante);
            }else{
                $checkAnneCourante= new AnneeAcademique();
                $aujourdhui= new \DateTime();
                $annee=$aujourdhui->format('Y');
                // dd($annee);
                $checkAnneCourante->setDebut($annee);
                $checkAnneCourante->setIsCurrent(true);
                $entreprise->setAnneeAcademiqueCourante($checkAnneCourante);

            }
            $entityManager->persist($checkAnneCourante);
            $entityManager->persist($entreprise);
            $entityManager->flush();

            if(!$this->getUser()){
                $checkUsers=$this->getDoctrine()->getRepository(User::class)->findAll();
                if(sizeof($checkUsers)==0){
                    return $this->redirectToRoute('app_register');
                }
                return $this->redirectToRoute('app_login');
            }

            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_show", methods={"GET"})
     */
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="entreprise_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entreprise $entreprise, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
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
                $entreprise->setLogo($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Opération réussie. ');

            return $this->redirectToRoute('entreprise_index');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entreprise_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entreprise $entreprise): Response
    {
        $this->addFlash('danger', 'Vous ne pouvez supprimer une entreprise existante. ');

        if ($this->isCsrfTokenValid('deleteXXXXXXXX'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entreprise_index');
    }
}

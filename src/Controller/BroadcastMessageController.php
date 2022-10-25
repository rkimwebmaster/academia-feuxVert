<?php

namespace App\Controller;

use App\Entity\BroadcastMessage;
use App\Entity\Fiche;
use App\Entity\Message;
use App\Entity\User;
use App\Form\BroadcastMessageEnseignantType;
use App\Form\BroadcastMessageType;
use App\Repository\BroadcastMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 *@IsGranted("IS_AUTHENTICATED_FULLY")
 * @Route("/broadcast/message")
 */
class BroadcastMessageController extends AbstractController
{
    /**
     * @Route("/", name="broadcast_message_index", methods={"GET"})
     */
    public function index(BroadcastMessageRepository $broadcastMessageRepository): Response
    {
        $user=$this->getUser();
        if($this->isGranted('ROLE_ADMIN')){
            $broadcastMessages = $broadcastMessageRepository->findAll();
            return $this->render('broadcast_message/index.html.twig', [
                'broadcast_messages' => $broadcastMessages,
            ]);
        }else{
            $broadcastMessages = $broadcastMessageRepository->findBy(['expediteur'=>$user]);
        }
        return $this->render('broadcast_message/index.html.twig', [
            'broadcast_messages' => $broadcastMessages,
        ]);
    }

    /**
     * @Route("/new", name="broadcast_message_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $broadcastMessage = new BroadcastMessage($this->getUser());
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $form = $this->createForm(BroadcastMessageEnseignantType::class, $broadcastMessage);
        } else {
            $form = $this->createForm(BroadcastMessageType::class, $broadcastMessage);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($this->isGranted('ROLE_ENSEIGNANT')) {
                $directeur = $this->getUser()->getEnseignant();
                $fichesDirecteur = $this->getDoctrine()->getRepository(Fiche::class)->findBy(['directeurRetenu' => $directeur]);
                foreach ($fichesDirecteur as $fiche) {
                    $destinataire = $fiche->getFinaliste();
                    $broadcastMessage->setGroupeDestinataire('Mes dirigés');
                    $message = new Message($broadcastMessage);
                    $message->setUserReceiver($destinataire->getUser());
                    $message->setIsNonLu(true);
                    $destinataire->getUser()->setNombreMessageNonLu($destinataire->getUser()->getNombreMessageNonLu() + 1);
                    $entityManager->persist($destinataire);
                    $entityManager->persist($message);
                }
            }else{
                $destinataires = $this->getDoctrine()->getRepository(User::class)->findAll();
                foreach ($destinataires as $destinataire) {
                    $message = new Message($broadcastMessage);
                    $message->setUserReceiver($destinataire);
                    $message->setIsNonLu(true);
                    $destinataire->setNombreMessageNonLu($destinataire->getNombreMessageNonLu() + 1);
                    $entityManager->persist($destinataire);
                    $entityManager->persist($message);
                }
            }
            $entityManager->persist($broadcastMessage);
            $entityManager->flush();
            $this->addFlash('success', 'Opération réussie. Vous avez envoyé un message de broadcast.');

            return $this->redirectToRoute('broadcast_message_index');
        }

        return $this->render('broadcast_message/new.html.twig', [
            'broadcast_message' => $broadcastMessage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="broadcast_message_show", methods={"GET"})
     */
    public function show(BroadcastMessage $broadcastMessage): Response
    {
        return $this->render('broadcast_message/show.html.twig', [
            'broadcast_message' => $broadcastMessage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="broadcast_message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BroadcastMessage $broadcastMessage): Response
    {
        $form = $this->createForm(BroadcastMessageType::class, $broadcastMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('broadcast_message_index');
        }

        return $this->render('broadcast_message/edit.html.twig', [
            'broadcast_message' => $broadcastMessage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="broadcast_message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BroadcastMessage $broadcastMessage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $broadcastMessage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($broadcastMessage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('broadcast_message_index');
    }
}

<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
*/
class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message_index")
     */
    public function index(MessageRepository $messageRepository): Response
    {
        $user=$this->getUser();
        $messages=$messageRepository->findBy(['userReceiver'=>$user],['createdAt'=>'DESC']);
        if(sizeof($messages)==0){
            $this->addFlash('info', 'Aucun nouveau message ');

        }else{
            $em=$this->getDoctrine()->getManager();
            foreach($messages as $message){
                $message->setIsNonLu(false);
                $em->persist($message);
            }
            $user->setNombreMessageNonLu(0);
            $em->persist($user);
            $em->flush();

        }
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/messageNonLu", name="message_non_lu_index")
     */
    public function indexNonLu(MessageRepository $messageRepository): Response
    {
        $user=$this->getUser();
        $messages=$messageRepository->findBy(['userReceiver'=>$this->getUser(),'isNonLu'=>true],['createdAt'=>'DESC']);
        if(sizeof($messages)==0){
            $this->addFlash('info', 'Aucun nouveau message ');

        }else{
            $em=$this->getDoctrine()->getManager();
            foreach($messages as $message){
                $message->setIsNonLu(false);
                $em->persist($message);
            }
            $user->setNombreMessageNonLu(0);
            $em->persist($user);
            $em->flush();

        }
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/messageLu", name="message_lu_index")
     */
    public function indexLu(MessageRepository $messageRepository): Response
    {
        $user=$this->getUser();
        $messages=$messageRepository->findBy(['userReceiver'=>$this->getUser(),'isNonLu'=>false],['createdAt'=>'DESC']);
        if(sizeof($messages)==0){
            $this->addFlash('info', 'Aucun nouveau message ');

        }else{
            $em=$this->getDoctrine()->getManager();
            foreach($messages as $message){
                $message->setIsNonLu(false);
                $em->persist($message);
            }
            $user->setNombreMessageNonLu(0);
            $em->persist($user);
            $em->flush();

        }
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }
}

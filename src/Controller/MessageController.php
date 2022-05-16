<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private MessageRepository $messageRepository;
    private TopicRepository $topicRepository;

    public function __construct(MessageRepository $messageRepository, TopicRepository $topicRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->topicRepository = $topicRepository;
    }

    #[Route('/messages/{id}', name: 'app_message')]
    public function index($id): Response
    {
        $topic = $this->topicRepository->find($id);
        $topicMessages = $topic->getMessages();

        return $this->render('message/index.html.twig', [
            'topic' => $topic,
            'topicMessages' => $topicMessages,
        ]);
    }

    #[Route('/messages/{id}/nouveau', name: 'app_message_new')]
    public function newMessage(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $topic = $this->topicRepository->find($id);

        $form = $this->createForm(MessageType::class, new Message());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $message->setCreadtedAt(new \DateTime());
            $message->setCreatedBy($user);
            $message->setTopic($topic);
            $entityManager->persist($message);
            $entityManager->flush();
            return  $this->redirectToRoute('app_message', ['id'=>$id]);
        }

        return $this->render('message/messageForm.html.twig', [
            'form' => $form->createView(),
            'topic' => $topic,
        ]);
    }

    #[Route('/messages/{id}/modifier', name: 'app_message_update')]
    public function updateMessage(Request $request, $id, EntityManagerInterface $entityManager, Message $message): Response
    {

        $getMessage = $this->messageRepository->find($id);
        dump($getMessage);
        $topic = $getMessage->getTopic();


        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return  $this->redirectToRoute('app_message', ['id' => $topic->getId()]);
        }

        return $this->render('message/messageForm.html.twig', [
            'form' => $form->createView(),
            'topic' => $topic,
        ]);
    }

    #[Route('/messages/{id}/supprimer', name: 'app_message_delete')]
    public function deleteMessage(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $message = $this->messageRepository->find($id);
        $topic = $message->getTopic();
        $entityManager->remove($message);
         $entityManager->flush();
         return $this->redirectToRoute('app_message', ['id'=>$topic->getId()]);

//        return $this->render('message/messageForm.html.twig', [
//            'form' => $form->createView(),
//            'topic' => $topic,
//        ]);
    }
}

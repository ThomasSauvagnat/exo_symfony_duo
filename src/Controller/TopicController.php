<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    // Liste de tous les topics
    // #[Route('/topic', name: 'app_topic')]
    // public function index(TopicRepository $topicRepository, PaginatorInterface $paginator, Request $request): Response
    // {
    //     $qb = $topicRepository->getQbAll();
    //     $pagination = $paginator->paginate(
    //         $qb,
    //         $request->query->getInt('page', 1),
    //         3
    //     );

    //     return $this->render('topic/index.html.twig', [
    //         'pagination' => $pagination,
    //     ]);
    // }


    // Ajouter un topic
    #[Route('/topic/ajouter/{id}', name: 'app_topic_add')]
    public function addTopic($id, EntityManagerInterface $em, Request $request, ForumRepository $forumRepository): Response
    {
        $form = $this->createForm(TopicType::class, new Topic());
        $form->handleRequest($request);

        // Récupérer un forum pour le lier au topic :
        $forum = $forumRepository->find($id);
        // dd($forum);
        
        // Récupérer le user :
        $user = $this->getUser();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $newTopic = $form->getData();
            $newTopic->setCreatedAt(new DateTime());
            $newTopic->setCreatedBy($user);
            $newTopic->setForum($forum);

            $em->persist($newTopic);
            $em->flush();

            // Pour faire passer un slug en php
            return $this->redirectToRoute('app_forum_detail', ['id' => $id]);
        }

         return $this->render('topic/add-topic.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Modifier un topic
    #[Route('/topic/modifier/{id}', name: 'app_topic_update')]
    public function updateTopic(Topic $topic, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        // Récupérer le user :
        $user = $this->getUser();

        // Récupérer le forum :
        $forum = $topic->getForum();

        if ($form->isSubmitted() && $form->isValid()) {
            $newTopic = $form->getData();
            $newTopic->setCreatedAt(new DateTime());
            $newTopic->setCreatedBy($user);
            $newTopic->setForum($forum);

            $em->persist($newTopic);
            $em->flush();

            return $this->redirectToRoute('app_forum_detail', ['id' => $forum->getId()]);
        }

         return $this->render('topic/update-topic.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // Détail d'un topic en fonction de son id (en twig récup les commentaires)
    #[Route('/topic/detail/{id}', name: 'app_topic_detail')]
    public function detailTopic($id, TopicRepository $topicRepository): Response
    {
        $topic = $topicRepository->find($id);
        // dd($topic);

        return $this->render('topic/detail-topic.html.twig', [
            'topic' => $topic
        ]);
    }


    // Supprimer un forum en fonction de son id
    #[Route('/topic/delete/{id}', name: 'app_topic_delete')]
    public function deleteTopic($id, TopicRepository $topicRepository, EntityManagerInterface $em): Response
    {
        $topic = $topicRepository->find($id);

        // °°°° Récupérer l'id du forum pour pouvoir retourner sur la page de tous les topics du forum en question :
        $forumId = $topic->getForum()->getId();
        
        $em->remove($topic);
        $em->flush();

        // °°°° Redirige vers la liste des topic d'un forum => lui passer en paramètre d'url l'id du forum en question
        return $this->redirectToRoute('app_forum_detail', ['id' => $forumId]);
    }

}

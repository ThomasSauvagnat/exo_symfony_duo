<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    private TopicRepository $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    #[Route('/forum/{id}/topic', name: 'app_topic')]
    public function index($id, ForumRepository $forumRepository): Response
    {
        $forum = $forumRepository->find($id);
        $allTopics = $forum->getTopics();
        dump($allTopics);

        return $this->render('topic/index.html.twig', [
            'topics' => $allTopics,
            'forum' => $forum,
        ]);
    }

    #[Route('/forum/{id}/topic/creation', name: 'app_topic_create')]
    public function newTopic(ForumRepository $forumRepository ,$id ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $forum = $forumRepository->find($id);
        $user = $this->getUser();
        $formTopic = $this->createForm(TopicType::class, new Topic());
        $formTopic->handleRequest($request);
        if($formTopic->isSubmitted() && $formTopic->isValid()) {
            $topic = $formTopic->getData();
            $topic->setCreatedAt(new \DateTime());
            $topic->setForum($forum);
            $topic->setCreatedBy($user);
            $entityManager->persist($topic);
            $entityManager->flush();
            return $this->redirectToRoute('app_topic', ['id' => $id]);
        }

        return $this->render('topic/topicForm.html.twig', [
            'formTopic' => $formTopic->createView(),
        ]);
    }
}

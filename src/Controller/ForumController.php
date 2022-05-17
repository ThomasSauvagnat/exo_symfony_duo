<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private ForumRepository $forumRepository;

    public function __construct(ForumRepository $forumRepository)
    {
        $this->forumRepository = $forumRepository;
    }

//    #[Route('/forum', name: 'app_forum')]
//    public function index(): Response
//    {
//        $allForum = $this->forumRepository->findAll();
//
//        return $this->render('forum/index.html.twig', [
//            'forums' => $allForum,
//        ]);
//    }

    #[Route('/forum/creer', name: 'app_forum_create')]
    public function forumForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formForum = $this->createForm(ForumType::class, new Forum());
        $formForum->handleRequest($request);
        if ($formForum->isSubmitted() && $formForum->isValid()) {
            $forum = $formForum->getData();
            $forum->setCreatedAt(new \DateTime());
            $entityManager->persist($forum);
            $entityManager->flush();
            return $this->redirectToRoute('app_forum');
        }

        return $this->render('forum/forumForm.html.twig', [
            'form' => $formForum->createView(),
        ]);
    }
    // Afficher la liste des forums
    #[Route('/forum', name: 'app_forum')]
    public function index(ForumRepository $forumRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $qb = $forumRepository->getQbAll();
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('forum/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    // Récupérer un forum en fonction de son id (et les topic en twig)
    #[Route('/forum/{id}', name: 'app_forum_detail')]
    public function show($id, ForumRepository $forumRepository): Response
    {
        $forum = $forumRepository->find($id);

        return $this->render('forum/forum-detail.html.twig', [
            'forum' => $forum
        ]);
    }


}

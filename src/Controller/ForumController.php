<?php

namespace App\Controller;

use App\Repository\ForumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
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

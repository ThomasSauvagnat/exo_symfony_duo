<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AddCommentType;
use App\Repository\CommentRepository;
use App\Form\CommentType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/jeux')]
class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(GameRepository $gameRepository): Response
    {

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $gameRepository -> findAll(),
        ]);
    }

    #[Route('/{slug}', name: 'app_game_details')]
    public function gameDetails($slug, GameRepository $gameRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $gameDetail = $gameRepository -> getGameDetails($slug);
        // Récupérer l'utilisateur co
        $user = $this->getUser();
        dump($user);

        // #### Pour ajouter form pour les comm
        // Récupère les jeux en fonction du slug => même qu'au dessus mais en passant par le repo
        $gameEntity = $gameRepository -> findOneBy(['slug' => $slug]);
        // Passe par une requete créé dans notre repo Comment qui récup un comment ou null
        $commentEntity = $commentRepository -> findOneByGameAndUser($gameEntity, $user);
        dump($commentEntity);

        $formComment = $this->createForm(AddCommentType::class, new Comment());
        $formComment->handleRequest($request);
        if($formComment->isSubmitted() && $formComment->isValid()) {
            $comment = $formComment->getData();
            $comment->setUpVotes(0);
            $comment->setDownVotes(0);
            $comment->setCreatedAt(new \DateTime());
            $comment->setAccount($user);
            $comment->setGame($gameEntity);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_game_details', ['slug' => $slug]);
        }

        return $this->render('game/gameDetails.html.twig', [
            'gameDetails' => $gameDetail,
            'gameRelated' => $gameRepository -> getRelatedGames($gameDetail),
            'user' => $user,
            'commentEntity' => $commentEntity,
            'form' => $formComment->createView(),
        ]);
    }

    #[Route('/{slug}/commentaires', name: 'app_game_comments')]
    public function gameComments($slug, GameRepository $gameRepository): Response
    {
        // dd($gameRepository -> getGameComment($slug));
        return $this->render('game/gameComments.html.twig', [
            'gameComment' => $gameRepository -> getGameComment($slug)
        ]);
    }


    // #### Version optimisé => voir GameRepository

    // #[Route('/{slug}', name: 'app_game_details')]
    // public function gameDetails($slug, GameRepository $gameRepository): Response
    // {

    //     return $this->render('game/gameDetails.html.twig', [
    //         'gameDetails' => $gameRepository -> getGameDetails($slug)
    //     ]);
    // }

    // #[Route('/{slug}/commentaires', name: 'app_game_comments')]
    // public function gameCommentd($slug, GameRepository $gameRepository): Response
    // {
    //     return $this->render('game/gameDetails.html.twig', [
    //         'gameDetails' => $gameRepository -> getGameDetails($slug, false)
    //     ]);
    // }
}

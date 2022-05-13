<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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
    public function gameDetails($slug, GameRepository $gameRepository, CommentRepository $commentRepository, Request $request, EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur co
        $user = $this->getUser();

        $gameDetail = $gameRepository -> getGameDetails($slug);

        // #### Pour ajouter form pour les comm
        // Récupère les jeux en fonction du slug => même qu'au dessus mais en passant par le repo
        $gameEntity = $gameRepository->findOneBy(['slug' => $slug]);
        // Passe par une requete créé dans notre repo Comment qui récup un comment ou null
        $commentEntity  = $commentRepository->findOnByGameAndUser($gameEntity , $user);

        // ### Pour le form 
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newComment = $form->getData();

            $newComment->setUpVotes(0);
            $newComment->setDownVotes(0);
            $newComment->setCreatedAt(new DateTime());
            $newComment->setAccount($user);
            $newComment->setGame($gameEntity);

            $em->persist($newComment);
            $em->flush();

            // Pour éviter que le form récupère les données du form une fois la soumision => redirige vers la même page et passe un second paramètre qui correspond au slug
            return $this->redirectToRoute('app_game_details', ["slug" => $slug]);

        }

        return $this->render('game/gameDetails.html.twig', [
            'gameDetails' => $gameDetail,
            'gameRelated' => $gameRepository -> getRelatedGames($gameDetail),
            'commentEntity' => $commentEntity,
            'form' => $form->createView()
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

<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function gameDetails($slug, GameRepository $gameRepository): Response
    {
        return $this->render('game/gameDetails.html.twig', [
            'gameDetails' => $gameRepository -> getGameDetails($slug)
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

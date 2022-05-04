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
}

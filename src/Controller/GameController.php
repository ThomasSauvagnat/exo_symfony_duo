<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/jeux', name: 'app_game')]
    public function index(GameRepository $gameRepository): Response
    {

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $gameRepository -> findAll(),
        ]);
    }
}

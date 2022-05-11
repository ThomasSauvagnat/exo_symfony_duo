<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(private GameRepository $gameRepository, private CommentRepository $commentRepository) {        
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
//        return $this->redirectToRoute('app_home');

        return $this->render('home/index.html.twig', [
            // Simple findBy avec les bon paramètres (pour requêtes assez simple)
            'lastGames' => $this->gameRepository->findBy([], ['publishedAt' => 'DESC'], 9),
            // 'lastComments' => $this -> commentRepository -> findBy([], ['createdAt' => 'DESC'], 4),
            'lastComments' => $this -> commentRepository -> lastComments(),
            'mostPlayedGames' => $this -> gameRepository -> getMostPlayedGames(),
            'mostBoughtGames' => $this -> gameRepository -> getMostBoughtGames(),
        ]);
    }

}

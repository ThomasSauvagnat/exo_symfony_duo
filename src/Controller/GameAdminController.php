<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Form\SearchGameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameAdminController extends AbstractController
{
    private GameRepository $gameRepository;
    private PaginatorInterface $paginator;

    public function __construct(GameRepository $gameRepository, PaginatorInterface $paginator)
    {
        $this->gameRepository = $gameRepository;
        $this->paginator = $paginator;
    }

    #[Route('/admin/game', name: 'app_game_admin')]
    public function index(Request $request): Response
    {
        $qb = $this->gameRepository->getQbAll();

        // Importation du formulaire
        $form = $this->createForm(SearchGameType::class);
        $form->handleRequest($request);
        // Traitement du formualire s'il est soumis
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            $qb = $this->gameRepository->updateQbByData($qb, $form->getData());
        }

        // pendant le traitement du formulaire => update notre qb
        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('game_admin/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/game/ajout', name: 'app_game_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this -> createForm(GameType::class, new Game());
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $game = $form -> getData();
            $game -> setSlug($game -> getName());
            $em -> persist($game);
            $em -> flush();
        }

        return $this->render('game_admin/gameAddForm.html.twig', [
            'form' => $form -> createView(),
        ]);
    }

    #[Route('/admin/game/{slug}', name: 'app_game_admin_details')]
    public function details($slug): Response
    {
        $gameDetails = $this -> gameRepository -> findOneBy(['slug' => $slug]);
        $gameCountries = $gameDetails -> getCountries();
        $gameGenres = $gameDetails -> getGenres();
        $gameComments = $gameDetails -> getComments();

        return $this->render('game_admin/gameDetailsAdmin.html.twig', [
            'game' => $gameDetails,
            'gameCountries' => $gameCountries,
            'gameGenres' => $gameGenres,
            'gameComments' => $gameComments
        ]);
    }

    #[Route('/admin/game/modifier/{slug}', name: 'app_game_update')]
    public function update(Game $game, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this -> createForm(GameType::class, $game);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $game -> setSlug($game -> getName());
            $em -> flush();
        }

        return $this->render('game_admin/gameUpdateForm.html.twig', [
            'form' => $form -> createView(),
        ]);
    }

}

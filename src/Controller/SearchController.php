<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    // Le nom de la route /search ne sera jamais affiché
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, GameRepository $gameRepository): Response
    {
        // /!\ Donner un nom différent des autres formulaires 
        $formSearch = $this->createForm(SearchType::class);
        $formSearch -> handleRequest($request);

        if ($formSearch -> isSubmitted() && $formSearch->isValid()) {
            // Récupérer la valeur de notre champ => le nom de notre input dans SearchType
            // Faire les différents traitements pour afficher une vue
            // dd($formSearch -> getData()['search']);

            $value = $formSearch -> getData()['search'];

            // Vérifie si la valeur est null
            if ($value === null) {
                return $this->redirectToRoute('app_game');
            } 

            $games = $gameRepository->getGameBySearch($value);

            if (count($games) === 1) {
                return $this->redirectToRoute('app_game_details', [
                    'slug' => $games[0]->getSlug()
                ]);
            } 
            if (count($games) > 1) {
                return $this->render('game/index.html.twig', [
                    'games' => $games ,
                ]);
            } 
            

            // Dans tous les autres cas retourne  sur home
            return $this->redirectToRoute('app_home');
        }


        return $this->render('search/index.html.twig', [
            'formSearch' => $formSearch->createView(),
        ]);
    }
}

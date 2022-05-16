<?php

namespace App\Twig;

use App\Entity\Game;
use App\Repository\GameRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GameExtension extends AbstractExtension
{

    // °°°° Fait appel au GameRepository pour pouvoir l'utiliser dans notre fonction getLastTenGames()
    // °°°° Ajout de l'objet Environnement => permet d'utiliser la méthode render qui retourne un template
    public function __construct(private GameRepository $gameRepository, private Environment $environment)
    {
        
    }


    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    // °°°° Création d'une fonction qui affiche les 10 derniers jeux (dans notre footer)
    // °°°° + fonction qui retounre les notes d'un jeux
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_last_ten_games', [$this, 'getLastTenGames']),
            new TwigFunction('get_stars_for_game', [$this, 'getStarsForGame']),
        ];
    }

    // public function doSomething($value)
    // {
    //     // ...
    // }

    // °°°° Fonction qui permet de récup les 10 derniers jeux 
    // Fait appel au GameRepository et utilise la fonction findLastGames que l'on avait déjà créé qui permet de récup les derniers jeux
    public function getLastTenGames(){

        $gameEntities = $this->gameRepository->findLastGames();
        return $gameEntities;
    }


    // °°°° Fonction qui récupère les notes d'un jeu
    // Passe en paramètre un game
    public function getStarsForGame(Game $game){

        $totalNote = 0;
        $sumNote = 0;

        // °°°° Boucle pour récupérer les comments d'un jeu (on trouve les note dans comments)
        foreach ($game->getComments() as $comment) {
            
            // °°°° Vérifie que les notes ne soient pas null 
            if ($comment->getNote() !== null) {
                $totalNote++;
                $sumNote += $comment->getNote();
            }
            if ($totalNote === 0) {
                return '<p>Aucune note</p>';
            } else {
                // return '<p>Il y a eu des notes</p>';

                // Calcul la somme
                $noteAvg = $sumNote /$totalNote;

                // °°°° Retourne notre template game/_stars.html.twig + on passe à notre template une variable 'note' (même principe que dans le controller)
                $html = $this->environment->render('game/_stars.html.twig', [
                    'note' => (int)$noteAvg
                ]);

                // °°°° Retourne la variable html => celle-ci sera retourné lorsqu'on utilise la fonction dans notre fichier _card.html.twig
                return $html;
            }
        }
    }
}

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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_last_ten_games', [$this, 'getLastTenGames']),
            new TwigFunction('get_stars_for_game', [$this, 'getStarsForGame']),
        ];
    }

    public function getLastTenGames()
    {
        $gameEntities = $this->gameRepository->findLastGames();
        return $gameEntities;
    }

    public function getStarsForGame(Game $game)
    {
        $totalNote = 0;
        $sumNote = 0;

        foreach ($game->getComments() as $comment) {
            if ($comment->getNote() !== null) {
                $totalNote++;
                $sumNote += $comment->getNote();
            }
        }
        if($totalNote === 0) {
            return '<p>Aucune note</p>';
        } else {
            $noteAvg = $sumNote / $totalNote;
            $html = $this->environment->render('game/_stars.html.twig', [
                'note' => (int)$noteAvg
            ]);
            return $html;
        }
    }
}

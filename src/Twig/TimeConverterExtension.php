<?php

namespace App\Twig;

use App\Service\TimeConverterService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimeConverterExtension extends AbstractExtension
{
    public function __construct(private TimeConverterService $timeConverterService) 
    { }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('time_to_string', [$this, 'timeToString']),
        ];
    }

    public function timeToString(int $gameTime): string
    {
        return $this -> timeConverterService -> convertTimeToString($gameTime);
    }
}

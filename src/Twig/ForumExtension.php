<?php

namespace App\Twig;

use App\Entity\Forum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ForumExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('count_message_by_forum', [$this, 'countMessageByForum']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    // °°°° Filtre sur un objet Forum
    // On veut le nb de msg par forum
    public function countMessageByForum(Forum $forum)
    {
        $numMessage = 0;

        // °°°° Boucle pour récupérer les topics
        foreach ($forum->getTopics() as $topic) {
            // °°°° Boucle pour récupérer les msg
            foreach ($topic->getMessages() as $message) {
                $numMessage++;
            }
        }
        // °°°° Incrémente le nb de msg
        return $numMessage;
    }
}

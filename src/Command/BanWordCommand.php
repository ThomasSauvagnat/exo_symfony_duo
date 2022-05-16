<?php

namespace App\Command;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ban-word',
    description: 'Add a short description for your command',
)]
class BanWordCommand extends Command
{
    private MessageRepository $messageRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(MessageRepository $messageRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    // Créer une commande qui va supprimer les messages du forum de la veille si ils contiennent des mots bannis !
    //  - Mot à tester : ['Pokemon', 'Digimon', 'Barbie', 'FromSoftSuck', 'UbisoftTheBest', 'BethesdaUnderatted']
    //  - Ajouter une propriété nbBanWord (int) à votre entitée Account qui de base est à 0
    //    - Des qu'un message est supprimé pour cause de mot bannis, augmenté la valeure de nbBanWord de 1
    //    - Sur la Home de votre site, si l'utilisateur est connecté, checker si il a + de 5 nbBanWord, si c'est la cas Affichez lui un gros Message dans un bandeau rouge qui dit : "PAS BIEN"
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $words = ['Pokemon', 'Digimon', 'Barbie', 'FromSoftSuck', 'UbisoftTheBest', 'BethesdaUnderatted'];
        $messages = $this->messageRepository->findAll();
        foreach ($messages as $message) {
            foreach ($words as $word) {
                $messageContent = $message->getContent();
                $messageAccount = $message->getCreatedBy();
                if (strpos($messageContent, $word)) {
                    $this->entityManager->remove($message);
                    $this->entityManager->flush();
                    $banWord = $messageAccount->setNbBanWord();
                    $banWord++;
                }
            }
        }
    }
}

<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\GameRepository;


#[AsCommand(
    name: 'app:change-game-name',
    description: 'Add a short description for your command',
)]
class ChangeGameNameCommand extends Command
{

    public function __construct(private GameRepository $gameRepository, private EntityManagerInterface $em)
    {
        // ## On doit récupérer le construct du parent car la class command étend la class parent ChangeGameNameCommand
        parent:: __construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'L\'id d\'un jeu existant')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupère l'id qui est passé par l'utilisateur à la cmd
        $id = $input -> getArgument('id');

        // Récupère un objet game en fonction de l'id
        $gameEntity = $this -> gameRepository -> find($id);

        // Vérifie si on à bien un Game
        if ($gameEntity === null) {

            // Si pas de game => renvoi un msg d'erreur à l'utilisateur 
            $output -> writeln('Jeu introuvable');
            return Command::FAILURE;
        } else {

            // Sinon je modifie le nom de notre entité et l'envoi en BDD
            $gameEntity -> setName('Toto');
            $this->em-> persist($gameEntity);
            $this->em->flush();

            // Retourne msg 
            $output->writeln("Le jeu avec l'id $id à maintenant le nom Toto");

            return Command::SUCCESS;
        }
    }
}

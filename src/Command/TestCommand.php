<?php

namespace App\Command;

use Prophecy\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-command',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    protected function configure(): void
    {
        $this
            // Ajouter un argument à notre cmd après appel de la cmd (ex : symfony console app:test-command azerty)
            // => nom / optionnel ou obligatoire / description
            ->addArgument('test_argument', InputArgument::REQUIRED, 'Argument description')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    // ## $input => récupérer ce qu'on a passé à notre cmd
    // ## $output => permet de retourner un msg à l'utilisateur
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ## stock la valeur de notre argument (celui du dessus) dans la variable $tstArgument
        $testArgument = $input -> getArgument('test_argument');
        dump($testArgument);

        // $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        // ## Retourner un status :
        // return Command::SUCCESS;

        // ## Retourne le msg Bonjour
        $output -> writeln('Bonjour');
        return Command::SUCCESS;
    }
}

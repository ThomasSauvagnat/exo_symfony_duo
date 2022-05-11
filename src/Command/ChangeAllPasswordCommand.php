<?php

namespace App\Command;

use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:change-all-password',
    description: 'Add a short description for your command',
)]
class ChangeAllPasswordCommand extends Command
{

    public function __construct(private AccountRepository $accountRepository, private EntityManagerInterface $em)
    {
        parent:: __construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->accountRepository->findAll();
        $nbAccounts = count($accounts);

        if ($nbAccounts === 0) {
            $output -> writeln('Auncun compte trouvé');
            return Command::FAILURE;
        } else {
            foreach ($accounts as $account) {
                $account->setPassword('$2y$13$4JO86NEOCZAMvZ0hbevNvekRibjbTEsSlxUWxTZxGff98r05mhW1a');
                $this->em->persist($account);
            }
    
            $this->em->flush();
            $output->writeln("Le mdp de $nbAccounts utilisateur a été modifié");
            return Command::SUCCESS;
        }
    }
}

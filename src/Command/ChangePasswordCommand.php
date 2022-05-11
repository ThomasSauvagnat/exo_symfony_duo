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
class ChangePasswordCommand extends Command
{
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->accountRepository = $accountRepository;
        $this->entityManager = $entityManager;
    }


    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this -> accountRepository -> findAll();
        if ($accounts == []) {
            $output->writeln('Compte(s) introuvable !');
            return Command::FAILURE;
        } else {
            foreach ($accounts as $account) {
                $account->setPassword('$2y$13$wCODREY9mCK3WyDhDLbrA.ll1CXqc9Ut3rub.SFBW7hTQG9SnvrkK');
                $this->entityManager->persist($account);
            }
            $output->writeln("Le mot de passe de " . count($accounts) . " comptes a été modifié !");
            $this->entityManager->flush();
            return Command::SUCCESS;
        }
    }
}

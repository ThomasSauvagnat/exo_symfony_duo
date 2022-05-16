<?php

namespace App\Command;

use App\Entity\Account;
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
    name: 'app:set-admin',
    description: 'Donne le rôle admin à un compte (via son email)',
)]
class SetAdminCommand extends Command
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
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Entrez l\'email du compte qui obtiendra le rôle d\'admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $account = $this->accountRepository->findOneBy(['email' => $email]);
        if ($account === null) {
            $output->writeln('Compte introuvable');
            return Command::FAILURE;
        } else {
            $account->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($account);
            $this->entityManager->flush();
            $output->writeln('Le compte avec l\'email ' . $email .' est maintenant admin');
            return Command::SUCCESS;
        }
    }
}

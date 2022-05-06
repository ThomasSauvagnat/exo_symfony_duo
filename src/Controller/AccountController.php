<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    public function __construct(private AccountRepository $accountRepository)
    {
        
    }

    // Attention à l'ordre des functions => si je la met dessous la fonction index, c'est la fonction index qui sera appelé car elle comment par utilisateur/et le slug comme il n'est pas défini peut être remplacé par nouveau => ne va pas su la bonne page

    // Ajouter new utilisateur
    #[Route('/utilisateur/nouveau', name: 'app_account_new')]
    public function newAccount(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AccountType::class, new Account());
        $form->handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form -> getData();
            $account -> setCreatedAt(new DateTime());
            $account -> setSlug($account->getName());
            $em -> persist($account);
            $em -> flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/newAccount.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Modifier utilisateur
    #[Route('/utilisateur/modifier/{slug}', name: 'app_account_edit')]
    public function editAccount(Account $account, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account -> setSlug($account->getName());
            $em -> flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/editAccount.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/utilisateur/comptes', name: 'app_account_all')]
    public function getAllAccounts(AccountRepository $accountRepository): Response
    {
        return $this->render('account/allAccount.html.twig', [
            'allAccounts' => $accountRepository->findAll()
        ]);
    }

    #[Route('/utilisateur/{name}', name: 'app_account_details')]
    public function index($name): Response
    {
        // dd($this -> accountRepository -> getAccountDetails($name));
        // dd($this -> accountRepository -> getTotalGameTime($name));
        return $this->render('account/index.html.twig', [
            'accountDetails' => $this -> accountRepository -> getAccountDetails($name),
            'accountTotalGameTime' => $this -> accountRepository -> getTotalGameTime($name),
        ]);
    }

    #[Route('/utilisateur/{name}/avis', name: 'app_account_comments')]
    public function show($name): Response
    {
        return $this->render('account/allAccountComments.html.twig', [
            'accountDetails' => $this -> accountRepository -> getAccountDetails($name),
        ]);
    }



}

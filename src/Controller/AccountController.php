<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    public function __construct(private AccountRepository $accountRepository)
    {
        
    }

    #[Route('/utilisateur/{name}', name: 'app_account_details')]
    public function index($name): Response
    {
        // dd($this -> accountRepository -> getAccountDetails($name));
        return $this->render('account/index.html.twig', [
            'accountDetails' => $this -> accountRepository -> getAccountDetails($name),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/utilisateur/{name}', name: 'app_account_details')]
    public function index($name, AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accountDetails' => $accountRepository -> getAccountDetails($name),
        ]);
    }
}

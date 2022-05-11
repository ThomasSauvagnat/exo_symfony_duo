<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class TestController extends AbstractController
{

    // public function __construct(private AccountRepository $accountRepository, private PaginatorInterface $paginatorInterface)
    // {
        
    // }

    // private AccountRepository $accountRepository;
    // private PaginatorInterface $paginatorInterface;
    // public function __construct(AccountRepository $accountRepository, PaginatorInterface $paginatorInterface)
    // {
    //     $this->accountRepository = $accountRepository;
    //     $this->paginatorInterface = $paginatorInterface;
    // }



    #[Route('/test', name: 'app_test')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        // // Pour récupéperer un utilisateur identifié
        // $user = $this->getUser();
        // dump($user);

        // $publisherEntity = new Publisher();
        // $form = $this->createForm(PublisherType::class, $publisherEntity);
        // // Permet de vérifier ce qui a été soumis et le set directement (lien entre le form et l'entité)
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     // Ajoute la date du jour => n'est pas a entrer dans le form => voir PublisherType
        //     $publisherEntity -> setCreatedAt(new DateTime());
        //     $publisher = $form->getData();
        //     $em->persist($publisher);
        //     $em->flush();
        // }



        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',

            // Créer la vue du formluaire
            // 'form' => $form -> createView()
        ]);
    }
}

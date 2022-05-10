<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\AccountRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private AccountRepository $accountRepository;
    private PaginatorInterface $paginator;

    public function __construct(AccountRepository $accountRepository, PaginatorInterface $paginator)
    {
        $this->accountRepository = $accountRepository;
        $this->paginator = $paginator;
    }


    #[Route('/test', name: 'app_test')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        // Pour récupéperer un utilisateur identifié
//        $user = $this->getUser();
//        dump($user);
//
//        $publisherEntity = new Publisher();
//        $form = $this->createForm(PublisherType::class, $publisherEntity);
//        // Permet de vérifier ce qui a été soumis et le set directement (lien entre le form et l'entité)
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Ajoute la date du jour => n'est pas a entrer dans le form => voir PublisherType
//            $publisherEntity -> setCreatedAt(new DateTime());
//            $publisher = $form->getData();
//            $em->persist($publisher);
//            $em->flush();
//        }

        // $qb pour QueryBuilder et on va chercher la fonction qu'on a créé
        // qui est juste un select * from account
        $qb = $this -> accountRepository -> getQbAll();

        $pagination = $this -> paginator -> paginate(
            $qb, /* LA QUERY */
            // On récupère le paramètre de l'URL et on le transforme en INT => Numéro de page
            $request -> query -> getInt('page', 1),
            // 3e argument : Nombre de résultat par page
            15
        );


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'pagination' => $pagination,

            // Créer la vue du formluaire
//            'form' => $form -> createView()
        ]);
    }
}

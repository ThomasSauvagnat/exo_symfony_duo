<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class PublisherAdminController extends AbstractController
{

    // Création du construct pour la pagination
    public function __construct(private PaginatorInterface $paginator)
    {
    }

    // Afficher la liste des éditeurs sur la page principal + pagination
    #[Route('/admin/editeurs', name: 'app_publisher_admin')]
    public function index(PublisherRepository $publisherRepository, Request $request): Response
    {
        // Les trier suivant le name
        // $publishers = $publisherRepository->findBy();

        // ## Pour réaliser une pagination :
        // stock tous les publishers dans la variable qb (passe par la fonction que l'on crée dans le repo)
        $qb = $publisherRepository->getQbAll();

        // Applique la méthode paginate à la propriété paginator qui prends 3 paramètres :
        // la requête, le paramètre d'url en get => page et sa valeur qui est donné à 1 par défaut 
        // puis le nb de résulat à afficher par page 
        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1), 
            10
        );


        // Retourne notre variable pagination à la vue => c'est un tableau
        return $this->render('publisher_admin/index.html.twig', [
            // 'publishers' => $publishers,
            'pagination' => $pagination
        ]);
    }


    // Ajouter un publisher
    #[Route('/admin/editeurs/ajouter', name: 'app_publisher_admin_add')]
    public function createPublisher(Request $request, EntityManagerInterface $em): Response
    {
        // Création d'un ojet publisher => sera passé en 2nd parametre de createdForm => permet de faire le lien avec le formulaire
        $publisher = new Publisher();

        // Création d'un formulaire qui corrspond au formulaire des publisher => en lien avec l'objet $publisher
        $form = $this->createForm(PublisherType::class, $publisher);
        // Fait le lien avec le post
        $form->handleRequest($request);

        // Vérifie si il est bien soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Création un newPublisher qui correspond au data du form
            $newPublisher = $form -> getData();
            // Comme la date est faite automatiquement et nom à la modification par le form (pas de champ dans publisherType) => on la modifie par le set
            $newPublisher -> setCreatedAt(new DateTime());

            // Le slug correspond au name => je le set automatiquement pour ne pas avoir à le rentrer dans le formulaire
            $newPublisher -> setSlug($newPublisher -> getName());

            // Met en file d'attente + envoi en bdd
            $em->persist($newPublisher);
            $em->flush();

            // Redirige sur la page d'accuil
            return $this->redirectToRoute('app_home');
        }

        // Retourne le formulaire
        return $this->render('publisher_admin/publisher-add-admin.html.twig', [
            'form' => $form -> createView()
        ]);
    }


    // // Modifier un editeur 
    #[Route('/admin/editeurs/modifier/{slug}', name: 'app_publisher_admin_update')]
    // Passe en 1er paramètre de fonction un publisher => fait le lien avec le slug et l'entité publisher => voir cours Kevin sur les routes
    public function updatePublisher(Publisher $publisher , Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        // Vérifie si il est bien soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Modifier toujours les valeurs des champs qui ne sont pas à modifier dans le form
            $publisher -> setCreatedAt(new DateTime());
            $publisher -> setSlug($publisher -> getName());

            // Plus besoin du persist()
            $em->flush();

            // Redirige sur la page d'accuil
            return $this->redirectToRoute('app_home');
        }

        return $this->render('publisher_admin/publisher-update-admin.html.twig', [
            'form' => $form -> createView()
        ]);
    }


    // Afficher la liste des jeux d'un publisher => en fonction de son slug
    #[Route('/admin/editeurs/{slug}', name: 'app_publisher_admin_details')]
    public function show($slug, PublisherRepository $publisherRepository): Response
    {
        // Récupérer un publishe en fonction de son slug => objet Publisher
        $publisher = $publisherRepository -> findOneBy(['slug' => $slug]);

        return $this->render('publisher_admin/publisher-detail-admin.html.twig', [
            'publisher' => $publisher
        ]);
    }

    // Supprimer un Publisher => en fonction de son slug
    #[Route('/admin/editeurs/supprimer/{slug}', name: 'app_publisher_admin_delete')]
    public function deletePublisher($slug, PublisherRepository $publisherRepository, EntityManagerInterface $em): Response
    {
        // Récupérer un publishe en fonction de son slug => objet Publisher
        $publisher = $publisherRepository -> findOneBy(['slug' => $slug]);

        $em->remove($publisher);
        $em->flush();

        return $this->redirectToRoute('app_publisher_admin');
    }


    


}

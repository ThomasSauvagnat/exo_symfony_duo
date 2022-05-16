<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Form\SearchForumType;
use App\Repository\ForumRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminForumController extends AbstractController
{

    // Affichier la liste des forums avec pagination + barre de recherche
    #[Route('/admin/forum', name: 'app_admin_forum')]
    public function index(ForumRepository $forumRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // $forums = $forumRepository->findAll();
        // dd($forums);

        // °°°° Récupère tous les forum
        $qb = $forumRepository->getQbAll();

        // °°°° Création d'une barre de recherche
        // Commence par créer notre barre de recherche qui est basé sur notre fichier SearchForumType
        // Pas d'objet à passer en second paramètre
        $form = $this->createForm(SearchForumType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->getData());
            // Récupère les donné quand le form est envoyé
            $datas = $form->getData();

            // Modifie notre requête qui selectionne tous les forum pour ne selectionner que les forums qui contienne ce que l'on rentre dans la barre de recherche
            $qb= $forumRepository->updateQbByData($qb, $datas);
        }


        // °°°° Pagination
        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('admin_forum/index.html.twig', [
            'pagination' => $pagination,

            // °°°° Passe à la vue le form
            'form' => $form->createView()
        ]);
    }

    // Ajouter un forum
    #[Route('/admin/forum/ajouter', name: 'app_admin_forum_add')]
    public function addForum(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ForumType::class, new Forum());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newForum = $form->getData();
            $newForum->setCreatedAt(new DateTime());

            $em->persist($newForum);
            $em->flush();

            return $this->redirectToRoute('app_admin_forum');
        }

        return $this->render('admin_forum/add-forum.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Modifier un forum
    #[Route('/admin/forum/modifier/{id}', name: 'app_admin_forum_update')]
    public function updateForum(Forum $forum, Request $request, EntityManagerInterface $em): Response
    {
        // dd($id);

        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newForum = $form->getData();
            $newForum->setCreatedAt(new DateTime());

            $em->persist($newForum);
            $em->flush();

            return $this->redirectToRoute('app_admin_forum');
        }

        return $this->render('admin_forum/update-forum.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Afficher un forum
    #[Route('/admin/forum/{id}', name: 'app_admin_forum_detail')]
    public function showForum($id, ForumRepository $forumrepository): Response
    {
        $forum = $forumrepository->find($id);;
        // dd($forum);

        return $this->render('admin_forum/detail-forum.html.twig', [
            'forum' => $forum
        ]);
    }


    // Supprimer un forum
    #[Route('/admin/forum/supprimer/{id}', name: 'app_admin_forum_delete')]
    public function deleteForum($id, ForumRepository $forumRepository, EntityManagerInterface $em): Response
    {
        
        $forum = $forumRepository->find($id);
        // dd($forum);
        $em->remove($forum);
        $em->flush();

        return $this->redirectToRoute('app_admin_forum');

        // return $this->render('admin_forum/update-forum.html.twig', [

        // ]);
    }


}

<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/admin/genre', name: 'app_genre')]
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('genre/index.html.twig', [
            'genres' => $genreRepository -> findAll(),
        ]);
    }

    #[Route('/admin/genre/ajouter', name: 'app_genre_add')]
    public function form(Request $request, GenreRepository $genreRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(GenreType::class, new Genre());
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {
            /** @var Genre $genre */
            $genre = $form->getData();
            $genre->setSlug($genre->getName());
            $em->persist($genre);
            $em->flush();
        }

        return $this->render('genre/genre-add-admin.html.twig', [
            'form' => $form -> createView(),
        ]);
    }

    #[Route('/admin/genre/modifier/{slug}', name: 'app_genre_update')]
    public function update(Genre $genre, $slug, Request $request, GenreRepository $genreRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form->isValid()) {
            $genre->setSlug($genre->getName());
            $em->flush();
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/genre-update-admin.html.twig', [
            'form' => $form -> createView(),
        ]);
    }

    #[Route('/admin/genre/{slug}', name: 'app_genre_details')]
    public function details($slug, GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository -> getGenre($slug);
        dump($genre);
        return $this->render('genre/genre_details.html.twig', [
            'games' => $genre -> getGames(),
            'genre' => $genre,
        ]);
    }

}

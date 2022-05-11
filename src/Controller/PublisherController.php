<?php

namespace App\Controller;

use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublisherController extends AbstractController
{
    #[Route('/editeur', name: 'app_publisher')]
    public function index(PublisherRepository $publisherRepository): Response
    {

        $publishers = $publisherRepository->getPublishersAll();

        $test = $publisherRepository->findAll();
        dump($test);
        dump($publishers);
        return $this->render('publisher/index.html.twig', [
            'publishers' => $publishers
        ]);
    }

    #[Route('/editeur/{slug}', name: 'app_publisher_details')]
    public function show($slug, PublisherRepository $publisherRepository): Response
    {
        return $this->render('publisher/publisherDetails.html.twig', [
            'publisher' => $publisherRepository->getPublisher($slug),
        ]);
    }


}

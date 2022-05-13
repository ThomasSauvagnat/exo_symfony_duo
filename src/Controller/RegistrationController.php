<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new Account();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Ajout des nouveaux champs de notre account qui ne sont pas génére par défaut
            $user->setWallet(0);
            // Avec la méthode slug de notre SluggerInterface => on passe en paramètre ce que l'on veut modifier pour que ce soit fait de la manière d'un slug => pas d'espace
            $slug = $slugger->slug($user->getName());
            // On modifie le slug, avec la variable du dessus
            $user->setSlug($slug);
            $user->setCreatedAt(new DateTime());

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // Permet de loger automatiquement l'utilisateur après s'être enregistré
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

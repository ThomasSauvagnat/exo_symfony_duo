<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // require : false => on peut envoyer un truc vide
            ->add('search', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un jeu',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // En utilisant     {{ render(controller(
    //   'App\\Controller\\SearchController::index'
    //   )) }}  
        // dans notre navbar, le formulaire pert son attribut action (attribut d'un form pour le traitement),
        // il faut donc lui définir cette attribut
        $resolver->setDefaults([
            'attr' => [
                'action' => '/search'  // Voir le nom de la route de notre controller searchController
            ]
        ]);
    }
}

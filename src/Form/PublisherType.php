<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Par défault, le type de chaque champ est un TextType 
            ->add('name', TextType::class, [

            ])
            ->add('website', TextType::class, [

                ])
            ->add('slug', TextType::class, [

                ])
            
                // Lorque c'est basé sur une entité => EntityType
            ->add('country', EntityType::class, [
                // Le nom de l'entité à laquelle la lié
                'class' => Country::class,
                // Choisir la propriété de notre entité à afficher => il faut la mettre en string
                'choice_label' => 'name'
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Permet de vérifier qu'on est basé sur une entité
            'data_class' => Publisher::class,
        ]);
    }
}

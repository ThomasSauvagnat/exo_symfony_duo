<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du jeu'
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix'
            ])
            ->add('publishedAt', DateType::class, [
                'label' => 'Date de sortie'
            ])
            ->add('description', TextareaType::class, [

            ])
            ->add('thumbnailCover', TextType::class, [
                'label' => 'Lien de l\'image'
            ])
            ->add('thumbnailLogo', TextType::class, [
                'label' => 'Lien du logo'
            ])

            ->add('countries', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true

            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('publisher', EntityType::class, [
                'class' => Publisher::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}

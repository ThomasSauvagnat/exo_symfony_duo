<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Publisher;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                // Modifier le nom du label
                'label' => 'Nom',
            ])
            ->add('website', TextType::class, [
                'label' => 'Site'
            ])
            // Ajouter une entité dans le formulaire
            ->add('country', EntityType::class, [
                'label' => 'Pays',

                // Choisir l'entité
                'class' => Country::class,
                
                // Permet d'aficher le nom des country par odre croissant
                'query_builder' => function (CountryRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },

                // Choisir la propriété de l'entité à afficher
                'choice_label' => 'name'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publisher::class,
        ]);
    }
}

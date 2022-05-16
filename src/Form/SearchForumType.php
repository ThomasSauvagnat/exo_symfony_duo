<?php

namespace App\Form;

use phpDocumentor\Reflection\PseudoTypes\False_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // °°°° Passer le formulaire en GET => sans ça lorsqu'on passe à une autre page de forum, on réaffiche tout et ne prend pas en compte les résultats des filtres
            ->setMethod('GET')
            ->add('title', TextType::class, [
                'label' => 'Nom',
                // °°°° Faire en sorte que le champ ne soit pas obligatoire
                'required' => False
            ])
            ->add('dateMini', DateType::class, [
                'label' => 'Date mini',

                // °°°° Permet d'ajouter un calendrier pour la recherche
                'widget' => 'single_text',

                'required' => False
            ])
            ->add('dateMax', DateType::class, [
                'label' => 'Date max',
                'widget' => 'single_text',
                'required' => False
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

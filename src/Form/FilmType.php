<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre: ",
                'attr' => [
                    'class' => 'col-11 d-block'
                ]
            ])
            ->add('year', NumberType::class, [
                'label' => "Année: ",
                'attr' => [
                    'class' => 'col-11'
                ]
            ])
            ->add('plot', TextareaType::class, [
                'label' => "Résumé: ",
                'attr' => [
                    'class' => 'col-11'
                ]
            ])
            ->add('poster', TextType::class, [
                'label' => "Affiche: ",
                'attr' => [
                    'class' => 'col-11'
                ]
            ])
            ->add('hasFilm', CheckboxType::class, [
                'label' => 'Je possède ce film: ',
                'mapped' => false,
                'required' => false,
            ])
            ->add('omdb_id', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Borrowing;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BorrowingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('borrowed', CheckboxType::class, [
                'label' => 'Prêter: ',
                'mapped' => false,
                'required' => false,
                'value' =>false,
            ])
            ->add('dateBorrowed', DateType::class, [
                'label' => 'Le',
                'widget' => 'single_text',
            ])
            ->add('borrower', EntityType::class, [
                'label' => 'À: ',
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => false,
                'expanded' => false,
                'by_reference' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Prêter',
                'attr' => ['class' => "btn btn-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrowing::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Rent;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('rental_start_date', null, [
            'widget' => 'single_text',
            'label' => 'Date de début de location',
        ])
        ->add('rental_end_date', null, [
            'widget' => 'single_text',
            'label' => 'Date de fin de location',
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
        ]);
    }
}

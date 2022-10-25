<?php

namespace App\Form;

use App\Entity\LignePlanification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignePlanification1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heureDebut')
            ->add('heureFin')
            ->add('observation')
            //->add('fiche')
            // ->add('planification')
            ->add('lecteurs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LignePlanification::class,
        ]);
    }
}

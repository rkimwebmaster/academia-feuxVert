<?php

namespace App\Form;

use App\Entity\AnneeAcademique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnneeAcademiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('isCurrent',null,[
            'label'=>'Cochez si c\'est l\'Année en cours',
        ])
        ->add('debut',null,[
            'label'=>'Début',
        ])
        ->add('fin', null, [
                'disabled'=>true,
            ])
            // ->add('resterSurPage',CheckboxType::class,[
            //     'mapped'=>false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnneeAcademique::class,
        ]);
    }
}

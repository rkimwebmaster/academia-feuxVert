<?php

namespace App\Form;

use App\Entity\Identite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdentiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('postnom')
            ->add('prenom')
            ->add('telephone',null,[
                'help'=>'+24397XXXXXXXXX',
                'attr'=>[
                    'value'=>'+243',
                ],
            ])
            ->add('adresse',null,[
                'label'=>'Adresse maison',
                'help'=>'Format : Numéro maison, Avenue, Quartier.',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Identite::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Proposition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet',null,[
                'required'=>'required',
                'help'=>'Le sujet doit avoir au moins 68 caractères.',
            ])
            ->add('resume',null,[
                'label'=>'Résumé',
                'required'=>'required',
                'help'=>'Le résumé doit avoir au moins 500 caractères.',

            ])
            //->add('fiche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proposition::class,
        ]);
    }
}

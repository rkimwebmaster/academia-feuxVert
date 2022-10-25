<?php

namespace App\Form;

use App\Entity\Depot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',null, [
                'disabled'=>'disabled',
            ])
            ->add('fichier',null,[
                'value'=>'Introduction / Mon premier chapitre...',
                'attr'=>[
                    'value'=>'Introduction / Mon premier chapitre...'
                ]
            ])
            ->add('noteEtudiant')
            ->add('noteDirecteur')
          //  ->add('fiche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depot::class,
        ]);
    }
}

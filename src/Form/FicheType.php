<?php

namespace App\Form;

use App\Entity\Fiche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('date',null,[
            //     'disabled'=>true,
            // ])
            // ->add('numero', null, [
            //     'label'=>'Numéro',
            //     'disabled'=>'disabled',
            // ])
            // ->add('isValidee')
            
            ->add('directeurPropose',null,[
                'label'=>'Directeur proposé',
                'attr'=>[
                    'class'=>'select2'
                ],
            ])
            ->add('propositions',CollectionType::class,[
                'entry_type'=>PropositionType::class,
                'allow_add'=>true,
                'allow_delete'=>true,
                //chager et mis a true 
                'by_reference'=>true,
                'label'=>false,
                ]			
                )
                ->add('isSoumis',null,[
                    'label'=>'Soumettre directement ' ,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class,
        ]);
    }
}

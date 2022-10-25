<?php

namespace App\Form;

use App\Entity\Codirecteur;
use App\Entity\Fiche;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FicheTraiterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('date',null, [
        //     'label'=>'date de soumission',
        //     'disabled'=>'disabled',
        // ])
        // ->add('dateValidation',null, [
        //     'disabled'=>'disabled',
        // ])
        // ->add('numero', null, [
        //     'disabled'=>'disabled',
        // ])
        // ->add('directeurPropose',null,[
        //     'disabled'=>'disabled',
        //     ])
            // ->add('isValidee')
            // ->add('isSoumis')
            // ->add('propositions',CollectionType::class,[
            //     'entry_type'=>PropositionTraiterType::class,
            //     'allow_add'=>true,
            //     'allow_delete'=>true,
            //     //chager et mis a true 
            //     'by_reference'=>true,
            //     'label'=>false,
            //     ]			
            //     )
                ->add('directeurRetenu',null,[
                    'help'=>'Indiquez le directeur retenu pour ce mémoire',
                    'required'=>'required',
                    'attr'=>[
                        'class'=>'select2'
                    ],
                ])
                // ->add('codirecteur')
                ->add('sujetRetenu',TextType::class,[
                    'required'=>'required',
                    'help'=>'Placez ici le sujet tel que retenu dans sa nouvelle formulation.',
                ])
                ->add('observation', CKEditorType::class,[
                    'empty_data'=>'rien à signaler',
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

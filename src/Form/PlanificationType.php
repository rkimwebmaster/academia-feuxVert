<?php

namespace App\Form;

use App\Entity\Fiche;
use App\Entity\LignePlanification;
use App\Entity\Planification;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',null,[
                'label'=>'date et heure début défense',
            ])
            ->add('salle',null,[
                'attr'=>[
                    'class'=>'select2'
                ],
            ])
            ->add('minutesDefense')
            ->add('minutesPause')
            ->add('fiches', EntityType::class, [
                'mapped' => false,
                'class' => Fiche::class,
                'multiple' => 'multiple',
                'choice_label' => 'finaliste',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                    ->where('f.etatFiche = 4')
                    // ->where('f.isPaiementDepot = true')
                    ->orderBy('f.id', 'ASC');
                },
            ])
            // ->add('lignePlanifications',CollectionType::class,[
            //     'entry_type'=>LignePlanificationType::class,
            //     'allow_add'=>true,
            //     'allow_delete'=>true,
            //     //chager et mis a true 
            //     'by_reference'=>true,
            //     'label'=>false,
            //     'mapped'=>false,
            //     ]			
            //     )
            ->add('isValidee')
            ->add('observation', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Planification::class,
        ]);
    }
}

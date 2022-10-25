<?php

namespace App\Form;

use App\Entity\Faculte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaculteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation')
            ->add('description')
            // ->add('departements', CollectionType::class, [
            //     'entry_type' => DepartementType::class,
            //     'entry_options' => ['label' => false],
            //     'label' => false,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference'=>false,
            // ])
            // ->add('resterSurCettePage',CheckboxType::class,[
            //     'mapped'=>false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Faculte::class,
        ]);
    }
}

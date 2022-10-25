<?php

namespace App\Form;

use App\Entity\Finaliste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinalisteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('identite',IdentiteType::class)
        ->add('promotion')
        ->add('civilite', ChoiceType::class, [
            'choices' => [
                'Madame' => 'Mme. ',
                'Monsieur ' => 'Mr. ',                
            ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Finaliste::class,
        ]);
    }
}

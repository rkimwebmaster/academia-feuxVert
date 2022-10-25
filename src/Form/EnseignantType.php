<?php

namespace App\Form;

use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('isCordirecteur')
            // ->add('nombreDirection')
            ->add('identite', IdentiteType::class)
            ->add('adresseBureau')
            ->add('grade', ChoiceType::class, [
                'choices' => [
                    'Assistant' => 'Ass. ',
                    'Professeur ' => 'Prof. ',
                    'Chef des Travaux' => 'C.T. ',
                    
                ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
}

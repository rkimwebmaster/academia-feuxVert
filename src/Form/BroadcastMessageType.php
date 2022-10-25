<?php

namespace App\Form;

use App\Entity\BroadcastMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use FOS\CKEditorBundle\Form\Type\CKEditorType;


class BroadcastMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('groupeDestinataire', ChoiceType::class, [
                'choices' => [
                    'Tous' => 'Tous',
                    'Finaliste' => 'ROLE_FINALISTE',
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Financier' => 'ROLE_FINANCIER',
                    'Chargé de faculté ' => 'ROLE_ADMIN',
                    
                ],
                ])
            ->add('contenu',CKEditorType::class)
            // ->add('expediteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BroadcastMessage::class,
        ]);
    }
}

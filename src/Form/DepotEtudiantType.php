<?php

namespace App\Form;

use App\Entity\Depot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class DepotEtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('date', null, [
            //     'disabled' => 'disabled',
            // ])
            ->add('titre', null, [
                'attr' => [
                    'placeholder' => 'Introduction version 2 / Mon premier chapitre V1 ...'
                ]
            ])
            ->add('brochure', FileType::class, [
                'label' => 'Joindre fichier (PDF ou Word)',
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'text/plain',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('noteEtudiant', CKEditorType::class, [
                'help' => 'Décrivez les changements effectués et dites quelque chose au Directeur...',
                'label' => 'Note étudiant',
                
            ])
            ->add('demandezRendezVous', null, [
                'help' => 'Cochez si vous voulez demandez un rendez-vous à votre Directeur.',
            ])
            // ->add('noteDirecteur')
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

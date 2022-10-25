<?php

namespace App\Form;

use App\Entity\Depot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;



class DepotEnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder
            // ->add('date',null, [
            //     'disabled'=>'disabled',
            // ])
            // ->add('fichier',null,[
            //     'disabled'=>'disabled',
            // ])
            // ->add('noteEtudiant',null,[
            //     'disabled'=>'disabled',
            // ])
            // ->add('fichierCorrigeDirecteur',null,[
            //     'label'=>'Le corigé du directeur ',
            //     'help'=>'Vous pouvez aussi soumettre un fichier word ',
            // ])
            ->add('brochure', FileType::class, [
                'label' => 'Fichier (PDF ou fichier word)',
                'mapped' => false,
                'help'=>'Vous pouvez remettre à l\'étudiant un fichier qui puisse le servir de modèle. Livres, cours, articles... ',

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
                        'mimeTypesMessage' => 'Télécharger un format valide svp.',
                    ])
                ],
            ])
            ->add('noteDirecteur',CKEditorType::class,[
                'required'=>'required',
            ])
            // ->add('dateRendezVous', null, [
            //     'help' => 'Precisez une date de rendez-vous si necessaire.',
            // ])
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

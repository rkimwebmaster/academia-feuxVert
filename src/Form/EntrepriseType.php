<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\PreferredChoice;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('ville')
            ->add('pays',CountryType::class,[
                'preferred_choices'=>['CD'],
                'attr'=>[
                    'class'=>'select2',
                ],
            ])
            ->add('email')
            ->add('telephone1',null ,[
                'help'=>'+24397XXXXXXXXX',
                'attr'=>['value'=>'+243'],
            ])
            ->add('telephone2',null,[
                'help'=>'+24397XXXXXXXXX',
                'attr'=>['value'=>'+243'],

            ])
            ->add('website', UrlType::class)
            ->add('isPublique')
            ->add('sigle')
            // ->add('logo')
            ->add('brochure', FileType::class, [
                'attr'=>[
                    'id'=>'#user_pictureFile'
                ],
                'label' => 'Logo (Fichier image)',
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
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
            ->add('monaie', ChoiceType::class, [
                'choices' => [
                    'Dollars US' => '$',
                    'Franc Congolais' => 'CDF',                    
                ],
                ])
                ->add('prixDepot', MoneyType::class)
                ->add('devise',null,[
                    'label'=>'Notre devise',
                ])
            ->add('boitePostale')
                ->add('dateFinPropositionSujet')
            ->add('dateDebutDefenseSession1')
            ->add('dateDebutDefenseSession2')
            ->add('dateCollationGrade')
            ->add('anneeAcademiqueCourante',null,[
                'help'=>'Si ce champs est laissé vide, l\'année en cours sera considéré comme début de l\'année acdémique.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}

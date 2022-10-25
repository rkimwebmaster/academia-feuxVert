<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RegistrationEnseignantFormType extends AbstractType
{
    private $role=['ROLE_ENSEIGNANT'];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username',null,[
            'label'=>'Nom utilisateur',
            'help'=>'le Nom d\'utilisateur doit avoir plus de 3 caractères',
        ])
        ->add('email', EmailType::class)
        ->add('enseignant',EnseignantType::class,[
        ])
        ->add('roles', HiddenType::class, [
            'empty_data'=> $this->role,
            ])
        ->add('agreeTerms', CheckboxType::class, [
            'label' => 'Cochez cette case',
            'mapped' => false,
            'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez cochez la case pour continuer.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'help'=>'le mot de passe doit avoir plus de 7 caractères. Inclure des caractères spéciaux avec chiffres et lettres.',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer le mot de passe, SVP.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit avoir au moins  {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

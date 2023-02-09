<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'disabled'=>true,
                'label'=>'Mon adresse email'
            ])
            ->add('firstname', TextType::class,[
                'disabled'=>true,
                'label'=>'Mon prenom'
            ])

            ->add('old_password',PasswordType::class,[
                'label'=>'Mon mot de passe actuel',
                'mapped'=>false,
                'attr'=>[
                    'placeholder'=> 'veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class,[
                'type'=>PasswordType::class,
                'mapped'=>false,
                'invalid_message'=>"le mot de passe et la confirmation doivent etre identique.",
                'label'=> "mon nouveau de passe",
                'required'=> true,
                'first_options'=>['label'=>' Mon nouveau mot de passe'],
                'second_options'=>['label'=>'Merci de Confirmer votre nouveau mot de passe'],
            ])

            ->add('submit', SubmitType::class,[
                'label'=>"Mettre à jour",
                'attr'=> ['class'=> 'btn-primary']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

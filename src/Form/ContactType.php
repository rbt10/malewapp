<?php

namespace App\Form;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, [
                'label'=>'Votre prénom',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname',TextType::class, [
                'label'=>'Votre nom',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class,[
                'label'=>'Votre Email',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre email'
                ]
            ])
            ->add('Message', TextareaType::class)
            ->add('submit', SubmitType::class,[
                'label'=>"Nous contacter",
                'attr'=>[
                    'class'=>'btn-block btn-outline-success my-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('firstname',TextType::class, [
                'label'=>'Votre prénom'
            ])
            ->add('lastname',TextType::class, [
                'label'=>'Votre nom'
            ])
            ->add('email', EmailType::class,[
                'label'=>'Votre Email',
            ])
            ->add('pseudo', TextType::class, [
                'label'=>'Votre prénom'
            ])
            ->add('website')
            ->add('date_naissance', DateType::class, [
                'label'=>'Date de naissance'
            ])
            ->add('submit', SubmitType::class,[
                'label'=>"Modifier",
                'attr'=>[
                    'class'=>'btn-block btn-outline-success my-3'
                ]
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

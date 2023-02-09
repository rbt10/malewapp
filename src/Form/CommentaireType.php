<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email', EmailType::class,[
                'label'=>'Mon adresse email'
            ])
            ->add('pseudo',TextType::class,[
                'label'=>'Mon pseudo'
            ])
            ->add('contenu',TextareaType::class)
            ->add('rgpd', CheckboxType::class)
            ->add('response', HiddenType::class,[
                'mapped' => false
            ])
            ->add('submit', SubmitType::class,[
                'label'=>"Envoyer",
                'attr'=>[
                    'class'=>'btn-block btn-outline-success my-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}

<?php


namespace App\Form;


use App\Classe\Search;
use App\Entity\Categorie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string',TextType::class, [
                'label'=> false,
                'required'=> false,
                'attr'=>[
                    'placeholder'=>' recette, viande , legumes ...',
                    'class'=>'form-control-sm',
                    'autocomplete' => true
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label'=>"Rechercher",
                'attr'=>[
                    'class'=>'btn btn-outline-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
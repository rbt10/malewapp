<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Difficulte;
use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\SpecialiteProvince;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de la recette'
            ])
            ->add('duree', TimeType::class,[
                'label'=>"Temps de préparation"
            ])
            ->add('difficulte',EntityType::class, [
                'class' => Difficulte::class,
                'label'=> 'Niveau de difficulté',
                'required' =>true
            ])
            ->add('category', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Type de plat',
                'required' =>true
            ])
            ->add('ingredients',  EntityType::class, [
                'class' => Ingredient::class,
                'label' => 'choisissez vos ingrédients(un à plusieurs ingrédients au choix)',
                'attr'=>[
                    'class'=>'js-example-basic-single'
                ],
                'multiple'=> true,
                'required' =>true
            ])

            ->add('imageFile', VichFileType::class, [
                'label' => 'Image(JPG ou PNG)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => true,
                'download_label' => false,
                'asset_helper' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger une image valide',
                    ])
                ],
            ])


            ->add('province', EntityType::class,[
                'class' => SpecialiteProvince::class,
                'attr'=>[
                    'class'=>'js-example-basic-single'
                ],
                'required'=>false,
            ])
            ->add('description')
            ->add('isPublic')
            ->add('submit', SubmitType::class,[
                'label'=>"Valider",
                'attr'=>[
                    'class'=>'btn-block btn-outline-success my-3'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use FakerRestaurant\Provider\fr_FR\Restaurant;


class CatgoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        $food = Faker\Factory::create('fr_FR');
        $food =  new Restaurant($food);

        $categories = [
            1 =>[
                'libelle_categorie' => 'entrée'
            ],
            2 =>[
                'libelle_categorie' => 'plat principal'
            ],
            3 =>[
                'libelle_categorie' => 'dessert'
            ],
            4 =>[
                'libelle_categorie' => 'appéritif'
            ]
        ];

        foreach ($categories as $key=> $value)
        {
            $categorie = new Categorie();
            $categorie->setLibelleCategorie($value['libelle_categorie']);

            $manager->persist($categorie);
            $this->addReference('cat-'.$key, $categorie);
        }


        $manager->flush();

    }


}

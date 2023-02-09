<?php

namespace App\DataFixtures;

use App\Entity\Difficulte;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use FakerRestaurant\Provider\fr_FR\Restaurant;


class DifficulteFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        $food = Faker\Factory::create('fr_FR');
        $food =  new Restaurant($food);


        $difficultes = [
            1 =>[
                'libelle_difficulte' => 'facile'
            ],
            2 =>[
                'libelle_difficulte' => 'niveau moyen'
            ],
            3 =>[
                'libelle_difficulte' => 'très facile'
            ],
            4 =>[
                'libelle_difficulte' => 'difficile'
            ]
        ];

        foreach ($difficultes as $key=> $value)
        {
            $difficulte = new Difficulte();
            $difficulte->setLibelleDifficulte($value['libelle_difficulte']);

            $manager->persist($difficulte);
            $this->addReference('difficulte-'.$key, $difficulte);
        }

        $manager->flush();
    }

}

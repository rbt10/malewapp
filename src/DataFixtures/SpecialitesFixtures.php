<?php

namespace App\DataFixtures;

use App\Entity\SpecialiteProvince;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use FakerRestaurant\Provider\fr_FR\Restaurant;


class SpecialitesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 27; $i++)
        {
            $provinces = new SpecialiteProvince();
            $provinces->setLibelle($faker->city);

            $manager->persist($provinces);
            $this->addReference('prov-'.$i, $provinces);
        }

        $manager->flush();

    }


}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');


        for ($i =0 ; $i < 12; $i++){
            $user = new User();
            $user->setEmail($faker->email)
                ->setRoles(['ROLE_USER']);

            $hash = $this->hasher->hashPassword(
                $user,
                'password'
            );

                $user->setPassword($hash)
                ->setFirstname( $faker->firstName())
                ->setLastname( $faker->name())->setPseudo($faker->firstName)->setDateNaissance(new \DateTime('@'.strtotime('now')))
                ->setWebsite($faker->name);

            $manager->persist($user);

            $this->addReference('user-'.$i, $user);

        }
        $manager->flush();


    }

}

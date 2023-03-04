<?php

namespace App\DataFixtures;


use App\Entity\User;
use App\Repository\Post\PostsRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class FavoriteFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private RecetteRepository $postRepository,
        private UserRepository $userRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        $posts = $this->postRepository->findAll();

        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(0, 15); $i++) {
                $post->addFavorite(
                    $users[mt_rand(0, count($users) - 1)]
                );
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            AppFixtures::class
        ];
    }
}

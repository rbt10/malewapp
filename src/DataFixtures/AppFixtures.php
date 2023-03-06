<?php

namespace App\DataFixtures;

use App\Entity\Favoris;
use App\Entity\Ingredient;

use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;


class AppFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }


    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        $food = Faker\Factory::create('fr_FR');
        $food = new Restaurant($food);

        // Génération des ingrédients aléatoires
        $ingredients = [];

        for ($i = 0; $i < 30; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setIngredienNom($food->vegetableName());
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }


        // Génération des recette aléatoires
        for ($i = 0; $i < 20; $i++) {
            $users = [];
            $categorie = $this->getReference('cat-'.rand(1,4));
            $difficulte = $this->getReference('difficulte-'.rand(1,4));
            $user = $this->getReference('user-'.rand(1,10));

            $province = $this->getReference('prov-'.rand(1,26));

            $recette = new Recette();
            $recette->setName($food->foodName())
                ->setDifficulte($difficulte)
                ->setCategory($categorie)
                ->setDescription($faker->text(150))
                ->setPrice(3)
                ->setImage($faker->name)
                ->setIsBest(mt_rand(0,1)==1)
                ->setIsPublic(mt_rand(0,1)== 1)
                ->setSlug($this->slugger->slug($recette->getName()))
                ->setProvince($province)
                ->setDuree(new \DateTime('@'.strtotime('now')))
                ->setUser($user)
                ->setUpdatedAt(new \DateTimeImmutable('@'.strtotime('now')));


            for ($j = 0; $j < mt_rand(5,15); $j++) {
                $recette->addIngredient($ingredients[mt_rand(0, count($ingredients) -1)]);
            }
            $manager->persist($recette);

        }
        $manager->flush();

    }


    /**
     * @return string[]
     */
    public function getDependencies():array
    {
        // TODO: Implement getDependencies() method.
        return [
            CatgoriesFixtures::class,
            DifficulteFixtures::class,
            SpecialitesFixtures::class,
            UserFixtures::class
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    /**
     * @var SluggerInterface
     */
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \WW\Faker\Provider\Picture($faker));

        for ($c = 0; $c < 10; $c++) {
            $category = new Category();
            $category->setName($faker->department())
                     ->setSlug(mb_strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 30); $p++) {
                $product = new Product();
                $product->setName($faker->productName())
                        ->setPrice($faker->price(4000, 25000))
                        ->setSlug(mb_strtolower($this->slugger->slug($product->getName())))
                        ->setShortDescription($faker->paragraph())
                        ->setMainPicture($faker->pictureUrl(250,200))
                        ->setCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}

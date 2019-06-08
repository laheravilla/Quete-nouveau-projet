<?php


namespace App\DataFixtures;

use App\Entity\Article;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 1; $i <= 50; $i++) {
            $title = new Slugify();
            $article = new Article();
            $article->setTitle($title->generate($faker->sentence(6)));
            $article->setContent(mb_strtolower($faker->text));
            $article->setCategory($this->getReference('category_' . rand(0, 6)));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
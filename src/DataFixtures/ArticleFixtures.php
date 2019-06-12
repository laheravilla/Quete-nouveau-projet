<?php
namespace App\DataFixtures;
use App\Entity\Article;
use App\Entity\Tag;
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

        for ($i = 1; $i <= 10; $i++) {
            $tag = new Tag();
            $tags = TagFixtures::TAGS;
            $rand_keys = array_rand($tags, 2);
            $tag->setName($tags[$rand_keys[0]]);
            $manager->persist($tag);

            $article = new Article();
            $title = new Slugify();
            $article->setTitle($title->generate($faker->sentence(6)));
            $article->setContent(mb_strtolower($faker->text));
            $article->setCategory($this->getReference('category_' . rand(0, 6)));
            $article->addTag($tag);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
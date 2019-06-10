<?php


namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    const TAGS = [
        'rivality',
        'update',
        'language',
        'trading',
        'google',
        'facebook',
        'microsoft',
        'linux',
        'windows',
        'android',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::TAGS as $key => $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $this->addReference('tag_' . $key, $tag);
            $manager->persist($tag);
        }
       $manager->flush();
    }
}
<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');

        for ($i = 1; $i <= 10; $i++) {
            $author = new User();
            $author->setFirstName($faker->firstName);
            if ($i == 1) {
                $author->setEmail('author@monsite.com');
            } else {
                $author->setEmail($faker->email);
            }
            $author->setRoles(['ROLE_AUTHOR']);
            $author->setPassword($this->passwordEncoder->encodePassword(
                $author,
                'password'
            ));
            $manager->persist($author);
        }

        $manager->persist($author);

        $admin = new User();
        $admin->setFirstName($faker->firstName);
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'password'
        ));

        $manager->persist($admin);

        $manager->flush();
    }
}
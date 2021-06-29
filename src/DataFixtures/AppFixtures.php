<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setUsername(sprintf('username-%s', $i));
            $user->setEmail(sprintf('email%s@gmail.com', $i));
            $user->setCreatedAt(new DateTime());

            $salt = md5(uniqid());
            $user->setSalt($salt);

            $password = sprintf('123qwe%s', $i);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));

            $manager->persist($user);

            if ($i % 25 == 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
    }
}

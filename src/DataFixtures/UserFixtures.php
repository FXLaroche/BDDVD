<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private const USER_LIST = [
        ['email' => 'fx.laroche@gmail.com', 'password' => '123456', 'username' => 'Effixel', 'roles' => ['ROLE_ADMIN']],
        ['email' => 'madeleine.paul@gmail.com', 'password' => '123456', 'username' => 'MPaul', 'roles' => ['']],
        ['email' => 'anne.muller@gmail.com', 'password' => '123456', 'username' => 'AMuller', 'roles' => ['']],
        ['email' => 'jean.martin@gmail.com', 'password' => '123456', 'username' => 'JMartin', 'roles' => ['']],
    ];

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        foreach (self::USER_LIST as $user) {
            $newUser = new User();
            $newUser->setEmail($user['email'])
                ->setUsername($user['username'])
                ->setRoles($user['roles'])
                ->setPassword($this->passwordHasher->hashPassword($newUser, $user['password']));
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}

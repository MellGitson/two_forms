<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_MELL = 'user_mell';
    public const USER_ADMIN = 'user_admin';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer utilisateur standard
        $user = new User();
        $user->setUsername('mell');
        $hashedPassword = $this->hasher->hashPassword($user, 'canac');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $this->addReference(self::USER_MELL, $user);

        // Créer utilisateur admin
        $admin = new User();
        $admin->setUsername('admin');
        $hashedPassword = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($admin);
        $this->addReference(self::USER_ADMIN, $admin);

        $manager->flush();
    }
}

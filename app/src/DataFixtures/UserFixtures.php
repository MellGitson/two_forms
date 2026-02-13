<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
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
        $user->setEmail('mell@example.com');
        $user->setFirstName('Melissa');
        $user->setLastName('Johnson');
        $user->setProfilePicture('https://i.pravatar.cc/150?img=1');
        $user->setCreatedAt(new \DateTimeImmutable());
        $hashedPassword = $this->hasher->hashPassword($user, 'canac');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $this->addReference(self::USER_MELL, $user);

        // Créer utilisateur admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Manager');
        $admin->setProfilePicture('https://i.pravatar.cc/150?img=2');
        $admin->setCreatedAt(new \DateTimeImmutable());
        $hashedPassword = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_MODERATOR', 'ROLE_USER']);
        $manager->persist($admin);
        $this->addReference(self::USER_ADMIN, $admin);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }
}

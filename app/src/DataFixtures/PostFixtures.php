<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public const POST_SMARTPHONE = 'post_smartphone';
    public const POST_SYMFONY = 'post_symfony';
    public const POST_SALON = 'post_salon';

    public function load(ObjectManager $manager): void
    {
        $post1 = new Post();
        $post1->setTitle('Découvrez les derniers smartphones');
        $post1->setContent('Les nouveaux smartphones offrent des performances exceptionnelles avec des caméras améliorées et une meilleure autonomie de batterie.');
        $post1->setCreatedAt(new \DateTimeImmutable('2024-02-01 10:00:00'));
        $post1->setApproved(true);
        /** @var \App\Entity\Category $category */
        $category = $this->getReference(CategoryFixtures::CATEGORY_ELECTRONICS, Category::class);
        $post1->setCategory($category);
        $manager->persist($post1);
        $this->addReference(self::POST_SMARTPHONE, $post1);

        $post2 = new Post();
        $post2->setTitle('Guide complet Symfony 7');
        $post2->setContent('Apprenez à créer des applications web robustes avec Symfony 7. Ce guide couvre les bases, les formulaires, la base de données et bien plus.');
        $post2->setCreatedAt(new \DateTimeImmutable('2024-02-05 14:30:00'));
        $post2->setApproved(true);
        /** @var \App\Entity\Category $category */
        $category = $this->getReference(CategoryFixtures::CATEGORY_IT, Category::class);
        $post2->setCategory($category);
        $manager->persist($post2);
        $this->addReference(self::POST_SYMFONY, $post2);

        $post3 = new Post();
        $post3->setTitle('Aménagez votre salon avec style');
        $post3->setContent('Découvrez les meilleurs conseils pour décorer votre salon et le rendre confortable et élégant. Meubles, couleurs, éclairage...');
        $post3->setCreatedAt(new \DateTimeImmutable('2024-02-10 09:15:00'));
        $post3->setApproved(true);
        /** @var \App\Entity\Category $category */
        $category = $this->getReference(CategoryFixtures::CATEGORY_HOME, Category::class);
        $post3->setCategory($category);
        $manager->persist($post3);
        $this->addReference(self::POST_SALON, $post3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['posts'];
    }
}

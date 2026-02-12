<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public const CATEGORY_ELECTRONICS = 'category_electronics';
    public const CATEGORY_IT = 'category_it';
    public const CATEGORY_HOME = 'category_home';
    public const CATEGORY_LEISURE = 'category_leisure';

    public function load(ObjectManager $manager): void
    {
        $cat1 = new Category();
        $cat1->setName('Électronique');
        $manager->persist($cat1);
        $this->addReference(self::CATEGORY_ELECTRONICS, $cat1);

        $cat2 = new Category();
        $cat2->setName('Informatique');
        $manager->persist($cat2);
        $this->addReference(self::CATEGORY_IT, $cat2);

        $cat3 = new Category();
        $cat3->setName('Maison');
        $manager->persist($cat3);
        $this->addReference(self::CATEGORY_HOME, $cat3);

        $cat4 = new Category();
        $cat4->setName('Loisirs');
        $manager->persist($cat4);
        $this->addReference(self::CATEGORY_LEISURE, $cat4);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['categories'];
    }
}

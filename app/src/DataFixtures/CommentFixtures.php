<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $comment1 = new Comment();
        $comment1->setContent('Super article ! Très informatif et bien expliqué.');
        /** @var \App\Entity\Post $post */
        $post = $this->getReference(PostFixtures::POST_SMARTPHONE, Post::class);
        $comment1->setPost($post);
        /** @var \App\Entity\User $user */
        $user = $this->getReference(UserFixtures::USER_MELL, User::class);
        $comment1->setAuthor($user);
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setContent('Symfony est vraiment un excellent framework. Merci pour ce guide !');
        /** @var \App\Entity\Post $post */
        $post = $this->getReference(PostFixtures::POST_SYMFONY, Post::class);
        $comment2->setPost($post);
        /** @var \App\Entity\User $user */
        $user = $this->getReference(UserFixtures::USER_ADMIN, User::class);
        $comment2->setAuthor($user);
        $manager->persist($comment2);

        $comment3 = new Comment();
        $comment3->setContent('Les conseils sur la décoration sont vraiment utiles. Je vais essayer !');
        /** @var \App\Entity\Post $post */
        $post = $this->getReference(PostFixtures::POST_SALON, Post::class);
        $comment3->setPost($post);
        /** @var \App\Entity\User $user */
        $user = $this->getReference(UserFixtures::USER_MELL, User::class);
        $comment3->setAuthor($user);
        $manager->persist($comment3);

        $comment4 = new Comment();
        $comment4->setContent('Article excellent, continuez comme ça !');
        /** @var \App\Entity\Post $post */
        $post = $this->getReference(PostFixtures::POST_SYMFONY, Post::class);
        $comment4->setPost($post);
        /** @var \App\Entity\User $user */
        $user = $this->getReference(UserFixtures::USER_MELL, User::class);
        $comment4->setAuthor($user);
        $manager->persist($comment4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class, UserFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['comments'];
    }
}

<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        // $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
    for ($i = 0; $i < 50; $i++) {

        $user = new User();
        $user->setLogin($this->faker->firstName());
        $user->setPassword($this->faker->password());
        $user->setEmail($this->faker->email());
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        
        $article = new Article();
        $article->setTitle($this->faker->sentence(2));
        $article->setContent($this->faker->text());
        $article->setImage($this->faker->imageUrl(360, 360, 'animals', true, 'cat'));
        $article->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
        $article->setIdUser($user);
        $manager->persist($article);

        $comment = new Comment();
        $comment->setContent($this->faker->text());
        $comment->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
        $comment->setIdUser($user);
        $comment->setIdArticle($article);
        $manager->persist($comment);

        $manager->flush();
    }
}
}

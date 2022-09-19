<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
   
    }

    public function load(ObjectManager $manager): void
    {
    for ($i = 0; $i < 10; $i++) {
        $faker = Factory::create('fr_FR');
        $user = new User();
        $user->setLogin($faker->name());
        
        // $hashedPassword = $this->passwordHasher->hashPassword(
        //     $user,
        //     'plaintextPassword'
        // );
        $user->setPassword($faker->password());
        $user->setEmail($faker->email());
        $manager->persist($user);
        
        $article = new Article();
        $article->setTitle($faker->title());
        $article->setContent($faker->text());
        $article->setImage($faker->imageUrl());
        $article->setCreatedAt(\DateTimeImmutable::createFromMutable($faker()->datetime()));
        $article->setIdUser($user);
        
        $manager->flush();
    }
}
}

<?php

namespace App\Controller;

use App\Entity\Tweet;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixturesController extends AbstractController
{
    #[Route('/fixtures', name: 'app_fixtures')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $entityManager->createQuery('DELETE FROM App\Entity\Tweet')->execute();
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $tweet = new Tweet();
            $tweet->setContent($faker->realText(140));
            $tweet->setAuthor($faker->firstName() . ' ' . $faker->lastName());
            $tweet->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month', 'now')));
            $tweet->setLikes($faker->numberBetween(0, 100));
            $tweet->setRetweets($faker->numberBetween(0, 50));

            $entityManager->persist($tweet);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_tweet_index');
    }
}

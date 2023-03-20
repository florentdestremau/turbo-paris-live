<?php

namespace App\DataFixtures;

use App\Entity\Tweet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TweetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $tweet = new Tweet();
            $tweet->setContent($faker->realText(140));
            $tweet->setAuthor($faker->firstName() . ' ' . $faker->lastName());
            $tweet->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 month', 'now')));

            $manager->persist($tweet);
        }

        $manager->flush();
    }

    private function generateShortSentence($faker): string
    {
        do {
            $sentence = $faker->sentence();
            $sentence = preg_replace('/[^A-Za-z0-9\s]+/', '', $sentence);
            $sentence = substr($sentence, 0, 140);
        } while (strlen($sentence) > 140);

        return $sentence;
    }

}

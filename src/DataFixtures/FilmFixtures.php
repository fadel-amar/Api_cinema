<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        //initialiser Faker
        $faker = Factory::create("fr_FR");
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

        // Créer 15 films

        for ($i=0; $i<15; $i++) {
            $film = new Film();
            $film->setTitre($faker->movie);

            $film->setDuree(random_int(80, 150));
            $manager->persist($film);
        }

        $manager->flush();
    }
}

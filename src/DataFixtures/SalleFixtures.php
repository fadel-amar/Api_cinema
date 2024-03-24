<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SalleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        //initialiser Faker
        $faker = Factory::create("fr_FR");

        // Créer 15 Salles
        for ($i=0; $i<15; $i++) {

            $salle = new Salle();

            $salle->setNom($faker->unique()->word(2,true));
            $salle->setNbPlaces($faker->numberBetween(100, 200));

            $manager->persist($salle);
        }

        $manager->flush();
    }
}

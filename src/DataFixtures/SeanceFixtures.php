<?php

namespace App\DataFixtures;

use App\Entity\Seance;
use App\Repository\FilmRepository;
use App\Repository\SalleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeanceFixtures extends Fixture
{
    private FilmRepository $filmRepository;
    private SalleRepository $salleRepository;

    public function __construct(FilmRepository $filmRepository, SalleRepository $salleRepository)
    {
        $this->filmRepository = $filmRepository;
        $this->salleRepository = $salleRepository;
    }
    public function load(ObjectManager $manager): void
    {

        //initialiser Faker
        $faker = Factory::create("fr_FR");

        // Créer 15 Seances
        for ($i=0; $i<15; $i++) {

            $seance = new Seance();
            $seance->setDateProjection($faker->dateTimeBetween('-1 year', 'now'));
            $tarifNormal = $faker->randomFloat(2, 8, 25);
            $seance->setTarifNormal($tarifNormal);
            $seance->setTarifReduit($faker->randomFloat(2, 5, $tarifNormal - 1));

            $films = $this->filmRepository->findAll();
            $film = $faker->randomElement($films);
            $seance->setFilm($film);

            $salles = $this->salleRepository->findAll();
            $salle = $faker->randomElement($salles);
            $seance->setSalle($salle);



            $manager->persist($seance);


        }

        $manager->flush();
    }


}
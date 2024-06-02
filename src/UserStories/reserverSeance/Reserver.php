<?php

namespace App\UserStories\reserverSeance;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Seance;
use App\Repository\SeanceRepository;
use App\UserStories\reserverSeance\ReservationRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Reserver
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private SeanceRepository $seanceRepository;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, SeanceRepository $seanceRepository)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->seanceRepository = $seanceRepository;
    }

    public function execute(ReservationRequest $request, User $user)
    {
        $errors = null;

        // Valider les données de la requête
        $violations = $this->validator->validate($request);
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors = $violation->getMessage();
            }
            return $errors;
        }


        // Vérifier si la séance existe
        $seance = $this->seanceRepository->find($request->seanceId);

        if($seance == null) {
            $errors[] = "La seance n'existe pas";
            return $errors;
        }


        // Vérifier si la séance est passée
        $now = new \DateTime();
        if ($seance->getDateProjection() < $now) {
            $errors = "La séance est déjà passée.";
            return $errors;
        }


        // Vérifier s'il reste des places disponibles pour la séance
        $placesReservees = $this->entityManager->getRepository(Reservation::class)->countPlaceReservationsForSeance($seance);
        $placesDisponibles = $seance->getSalle()->getNbPlaces() - $placesReservees;
        if ($request->nbPlacesReserver > $placesDisponibles) {
            $errors = "Il n'y a pas suffisamment de places disponibles pour cette seance.";
            return $errors;
        }


        $montantTotal = $request->nbPlacesReserver * $seance->getTarifNormal();

        // Créer une nouvelle réservation
        $reservation = new \App\Entity\Reservation();
        $reservation->setNbPlacesAReserver($request->nbPlacesReserver);
        $reservation->setDateReservation(new \DateTime());
        $reservation->setMontantTotal($montantTotal);
        // Associer l'utilisateur à la réservation
        $reservation->setUser($user);

        // Associer la séance à la réservation
        $reservation->setSeance($seance);

        // Enregistrer la réservation
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return true;
    }
}

<?php

namespace App\UserStories\reserverSeance;


use Symfony\Component\Validator\Constraints as Assert;

class ReservationRequest
{
    #[Assert\NotBlank(message: "Le nombre de places à réserver est obligatoire")]
    #[Assert\Type(type:"integer" , message: "Le nombre de places doit être un entier")]
    #[Assert\Positive(message: "Le nombre de places doit être positif")]
    public int $nbPlacesReserver;

    #[Assert\NotBlank(message: "L'identifiant de la séance est obligatoire")]
    #[Assert\Type(type: "integer", message: "L'identifiant de la séance doit être un entier")]
    #[Assert\Positive(message: "L'identifiant de la séance doit être positif")]
    public int $seanceId;

    /**
     * @param int $nbPlacesReserver
     * @param int $seanceId
     */
    public function __construct( int $seanceId, int $nbPlacesReserver)
    {
        $this->nbPlacesReserver = $nbPlacesReserver;
        $this->seanceId = $seanceId;
    }


}
<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\FilmRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use App\UserStories\reserverSeance\ReservationRequest;
use App\UserStories\reserverSeance\Reserver;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api')]
class FilmController extends AbstractController
{
    #[Route('/films', name: 'api_film_index', methods: ['GET'])]
    #[OA\Tag(name: 'Film')]
    #[OA\Get(
        path: "/api/films",
        description: "Permet de récupérer la liste des Films",
        summary: "Lister ensemble Films",
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des films au format Json",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(type: Film::class, groups: ["list_films"])
                    )
                )

            )
        ]
    )]
    public function index(FilmRepository $filmRepository, SerializerInterface $serializer): Response
    {
        $films = $filmRepository->findAllFilmsAffiches();

        $filmsJson = $serializer->serialize($films, 'json', ['groups' => 'list_films']);

        return new Response($filmsJson, Response::HTTP_OK, ["content-type" => "application/json"]);
    }


    #[\Symfony\Component\Routing\Annotation\Route('/films/{id}', name: 'api_film_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Tag(name: 'Film')]
    #[OA\Get(
        path: "/api/films/{id}",
        description: "Permet de récupérer un film par son id",
        summary: "Récupérer un Film",
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'id du Film à rechercher',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Détail du Film au format Json",
                content: new OA\JsonContent(
                    ref: new Model(type: Film::class, groups: ["show_film"])
                )

            )
        ]
    )]
    public function show(FilmRepository $filmRepository, SerializerInterface $serializer, int $id): Response
    {
        $arrayFilms = $serializer->serialize($filmRepository->find($id), "json", ['groups' => 'show_film']);
        return new Response($arrayFilms, Response::HTTP_OK, ["content-type" => "application/json"]);
    }


    #[\Symfony\Component\Routing\Annotation\Route('/reservations/{id}', name: 'api_film_reservations', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[OA\Tag(name: 'Film')]
    #[OA\Post(
        path: "/api/reservations",
        description: "Permet de réserver ",
        summary: "Réserver séance",

        responses: [
            new OA\Response(
                response: 200,
                description: "La reservation",
                content: new OA\JsonContent(
                    ref: new Model(type: Reservation::class, groups: ["show_reservations"])
                )

            )
        ]
    )]
    public function createReservation($id, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, SeanceRepository $seanceRepository, Security $security, EntityManagerInterface $entityManager): Response
    {


        if ($request->isMethod('GET')) {

            $infoSeance = $serializer->serialize($seanceRepository->find($id), "json", ['groups' => 'show_reservations']);
            return new Response($infoSeance, Response::HTTP_OK, ["content-type" => "application/json"]);

        } else {
            $data = json_decode($request->getContent(), true);
            $userSecurity = $security->getUser();

            if ($userSecurity == null) {
                // Si l'utilisateur n'est pas authentifié, renvoyer une erreur
                return new Response(
                    $serializer->serialize(['success' => false, 'errors' => 'Utilisateur non connecté'], 'json'),
                    Response::HTTP_UNAUTHORIZED,
                    ['content-type' => 'application/json']
                );
            }

            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $userSecurity->getUserIdentifier()]);

            $reservationRequest = new ReservationRequest($data['idSeance'], $data['nbPlacesReserver']);
            $reservationService = new Reserver($entityManager, $validator, $seanceRepository);
            $result = $reservationService->execute($reservationRequest, $user);


            // Vérifier le résultat de la création de la réservation
            if ($result !== true) {
                return new Response(
                    $serializer->serialize(['success' => false, 'errors' => $result], 'json'),
                    Response::HTTP_BAD_REQUEST,
                    ['content-type' => 'application/json']
                );
            }

            // Si la réservation a été créée avec succès, renvoyer un message de succès
            return new Response(
                $serializer->serialize(['success' => true], 'json'),
                Response::HTTP_CREATED,
                ['content-type' => 'application/json']
            );
        }
    }


}

<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;


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
                response:200,
                description: "Liste des films au format Json",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref : new Model(type: Film::class,groups: ["list_films"])
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
                name:'id',
                description:'id du Film à rechercher',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response:200,
                description: "Détail du Film au format Json",
                content: new OA\JsonContent(
                    ref : new Model(type: Film::class,groups: ["show_film"])
                )

            )
        ]
    )]
    public function show(FilmRepository $filmRepository, SerializerInterface $serializer, int $id): Response
    {
        $arrayFilms = $serializer->serialize($filmRepository->find($id), "json", ['groups' => 'show_film']);
        return new Response($arrayFilms, Response::HTTP_OK, ["content-type" => "application/json"]);
    }



    





}

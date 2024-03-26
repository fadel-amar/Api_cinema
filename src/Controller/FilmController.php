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
        summary: "Lister ensemble Posts",
        responses: [
            new OA\Response(
                response:200,
                description: "Liste des posts au format Json",
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
        $films = $filmRepository->findAll();

        $filmsJson = $serializer->serialize($films, 'json', ['groups' => 'list_films']);

        return new Response($filmsJson, Response::HTTP_OK, ["content-type" => "application/json"]);

    }
}

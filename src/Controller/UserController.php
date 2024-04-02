<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use \Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    #[Route('/register', name: 'register_user', methods: ['POST'])]
    #[OA\Tag(name: 'Film')]
    #[OA\Post(
        path: "/api/register",
        description: "Permet de créer un compte",
        summary: "Créer un compte",
        responses: [
            new OA\Response(
                response: 200,
                description: "Création compte",
            )
        ]
    )]
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $bodyRequest = $request->getContent();
        $parameters = json_decode($request->getContent(), true);







        return $this->render('register/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}

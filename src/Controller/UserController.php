<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use App\UserStories\creerUser\Register;
use App\UserStories\creerUser\RegisterRequest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorBuilder;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\throwException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api')]
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
    public function index(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $bodyRequest = $request->getContent();
        $parameters = json_decode($request->getContent(), true);

        if (!isset($parameters['email']) || !isset($parameters['password']) || !isset($parameters['confirmPassword'])) {
            return new Response(
                $serializer->serialize(['error' => "Il manque des données"], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );
        }


        $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();

        $requete = new RegisterRequest($parameters['email'], $parameters['password'], $parameters['confirmPassword']);

        $register = new Register($entityManager, $validateur);

        $resultat = $register->execute($requete, $passwordHasher);

        if ($resultat !== true) {
            return new Response(
                $serializer->serialize(['success' => false, $resultat], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['content-type' => 'application/json']
            );

        } else {

            return new Response(
                $serializer->serialize(['success' => true], 'json'),
                Response::HTTP_CREATED,
                ['content-type' => 'application/json']
            );

        }


    }
}
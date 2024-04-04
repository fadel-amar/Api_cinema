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
    public function index(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        $bodyRequest = $request->getContent();
        $parameters = json_decode($request->getContent(), true);

        if (!isset($parameters->email) && !isset($parameters->password) && !isset($parameters->confirmPassword) ) {
            throw new \Exception("Il manque des données");
        }


        $validateur = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
        $request = new Register($entityManager , $validateur).




        return $this->render('register/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}

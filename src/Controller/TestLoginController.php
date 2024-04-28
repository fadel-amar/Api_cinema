<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api')]
class TestLoginController extends AbstractController
{
    #[Route('/testLogin', name: 'app_test_login')]
    public function index(): Response
    {
        return $this->render('test_login/index.html.twig', [
            'controller_name' => 'TestLoginController',
        ]);
    }
}

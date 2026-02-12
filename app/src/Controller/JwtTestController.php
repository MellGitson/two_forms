<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JwtTestController extends AbstractController
{
    #[Route('/jwt-test', name: 'jwt_test')]
    public function index(): Response
    {
        return $this->render('jwt_test/index.html.twig');
    }
}

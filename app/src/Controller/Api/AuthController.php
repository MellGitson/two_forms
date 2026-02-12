<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api')]
class AuthController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $hasher,
        JWTTokenManagerInterface $tokenManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            return $this->json([
                'error' => 'Missing username or password'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->findByUsername($username);

        if (!$user || !$hasher->isPasswordValid($user, $password)) {
            return $this->json([
                'error' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Générer le token JWT
        $token = $tokenManager->create($user);

        return $this->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/me', name: 'api_me', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]
        ], Response::HTTP_OK);
    }
}

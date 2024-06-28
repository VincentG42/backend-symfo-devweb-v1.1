<?php

// src/Controller/MeController.php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MeController extends AbstractController
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    #[Route('/api/user/me', name: 'api_users_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $userEmail = $this->getUser()->getUserIdentifier();

        if (!$userEmail) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findOneByEmail($userEmail);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'lastname' => $user->getLastname(),
            'firstname' => $user->getFirstname(),
            'roles' => $user->getRoles(),
            'team' => $user->getTeam(),
            'licence' => $user->getLicenceNumber(),
            'relationship' => $user->getRelationship(),
            'hasToChangePassword' => $user->hasToChangePassword(),
        ];

        return new JsonResponse($userData);
    }
    // public function __invoke(): JsonResponse
    // {
    //     $userEmail = $this->getUser()->getUserIdentifier();

    //     if (!$userEmail) {
    //         return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_UNAUTHORIZED);
    //     }

    //     $user = $this->userRepository->findOneByEmail($userEmail);

    //     if (!$user) {
    //         return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_NOT_FOUND);
    //     }

    //     $userData = [
    //         'id' => $user->getId(),
    //         'email' => $user->getEmail(),
    //         'lastname' => $user->getLastname(),
    //         'firstname' => $user->getFirstname(),
    //         'roles' => $user->getRoles(),
    //         'team' => $user->getTeam(),
    //         'relationship' => $user->getRelationship(),
    //         'hasToChangePassword' => $user->hasToChangePassword(),
    //         // Ajoute les autres informations nécessaires
    //     ];

    //     return new JsonResponse($userData);
    // }
}
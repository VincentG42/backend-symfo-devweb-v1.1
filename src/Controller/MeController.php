<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Serializer\SerializerInterface;


#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/user/me',
            name: 'api_user_me'
        )
    ]
)]
class MeController extends AbstractController
{
    private $userRepository;
    private $logger;
    private $serializer;
    
    public function __construct(UserRepository $userRepository, LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    #[Route('/api/user/me', name: 'api_user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        try{
        $userEmail = $this->getUser()->getUserIdentifier();

        if (!$userEmail) {
            return new JsonResponse(['error' => 'User not found'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $this->logger->info('User authenticated: ' . $userEmail);
        $user = $this->userRepository->findOneByEmail($userEmail);
        $this->logger->info('User found: ' . $userEmail);

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
            'hasToChangePassword' => $user->hasToChangePassword(),
            'userType' => $user->getUserType() ?$user->getUserType() ->getName() : null,
        ];

        $jsonContent = $this->serializer->serialize($user, 'json', ['groups' => ['user:read']]);

        return new JsonResponse($jsonContent,200, [], true);
        }catch(\Exception $e){
            $this->logger->error( 'Error in MeController: ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
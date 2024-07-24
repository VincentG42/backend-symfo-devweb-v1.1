<?php

namespace App\Controller;

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
    private $logger;
    private $serializer;
    
    public function __construct( LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    #[Route('/api/user/me', name: 'api_user_me', methods: ['GET'])]
    public function me(): JsonResponse
{
    try {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        $this->logger->info('User authenticated: ' . $user->getUserIdentifier());

        $jsonContent = $this->serializer->serialize($user, 'json', ['groups' => ['user:read']]);
        return new JsonResponse($jsonContent, 200, [], true);
    } catch(\Exception $e) {
        $this->logger->error('Error in MeController: ' . $e->getMessage());
        return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
    
}
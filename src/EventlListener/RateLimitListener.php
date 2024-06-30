<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RateLimitListener
{
    public function __construct(
        #[Autowire(service: 'limiter.api')]
        private RateLimiterFactory $apiLimiter
    ) {}

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        
        // Vérifiez si la requête concerne l'API
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        $limiter = $this->apiLimiter->create($request->getClientIp());
        
        if (false === $limiter->consume(1)->isAccepted()) {
            $event->setResponse(new JsonResponse(['message' => 'Trop de requêtes'], Response::HTTP_TOO_MANY_REQUESTS));
        }
    }
}
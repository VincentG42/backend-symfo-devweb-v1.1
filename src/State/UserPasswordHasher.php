<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Service\EmailService;
use App\Service\PasswordGenerator;

final class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $processor,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EmailService $emailService,
        private readonly PasswordGenerator $passwordGenerator
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof User) {
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        if ($data->getId() === null) {
            // C'est une création d'utilisateur
            $temporaryPassword = $this->passwordGenerator->generatePassword();
            $hashedPassword = $this->passwordHasher->hashPassword($data, $temporaryPassword);
            $data->setPassword($hashedPassword);

            // On envoie l'email avec le mot de passe temporaire
            $this->emailService->sendWelcomeEmail($data->getEmail(), $temporaryPassword);
        } elseif ($data->getPassword() !== null) {
            // C'est une mise à jour du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
            $data->setPassword($hashedPassword);
        }

        $result = $this->processor->process($data, $operation, $uriVariables, $context);

        return $result;
    }
}
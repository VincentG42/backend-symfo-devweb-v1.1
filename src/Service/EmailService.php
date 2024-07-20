<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendWelcomeEmail(string $to, string $tempPassword)
    {
        $email = (new Email())
            ->from('your@email.com')
            ->to($to)
            ->subject('Bienvenue ! Voici vos identifiants')
            ->text(<<<EOT
            Bienvenue  sur l'application du CCSLR!
            
            Voici vos identifiants de connexion :
            
            Identifiant : {$to}
            Mot de passe temporaire : {$tempPassword}
            
            Veuillez vous connecter à l'adresse suivante : https://votreapplication.com/login
            
            Nous vous recommandons de changer votre mot de passe lors de votre première connexion.
            
            Cordialement,
            L'équipe de votre application
            EOT
            );
            

        $this->mailer->send($email);
    }
}
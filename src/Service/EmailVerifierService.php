<?php

// src/Service/EmailVerifier.php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class EmailVerifierService
{
    private $verifyEmailHelper;
    private $mailer;
    private $router;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, UrlGeneratorInterface $router, EntityManagerInterface $entityManager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, $user, string $email): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $email,
            ['id' => $user->getId()]
        );

        $emailMessage = (new Email())
            ->from(new Address('main.g00gle.mail@gmail.com', 'Neurify'))
            ->to($email)
            ->subject('Please Confirm your Email')
            ->html('<p>To confirm your email, please click <a href="' . $signatureComponents->getSignedUrl() . '">here</a></p>');

        $this->mailer->send($emailMessage);
    }

    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation(
            $request->getUri(),
            $user->getId(),
            $user->getEmail()
        );

        $user->setIsVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

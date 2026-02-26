<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailVerifierService
{
    private $verifyEmailHelper;
    private $mailer;
    private $router;
    private $logger;
    private $senderEmail;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer,
        UrlGeneratorInterface $router,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        string $senderEmail
    ) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->senderEmail = $senderEmail;
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, string $email): bool
    {
        try {
            // Проверяем наличие ID
            if (null === $user->getId()) {
                throw new \RuntimeException('User must be saved to the database before sending an email confirmation.');
            }

            // Генерация подписи
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                $verifyEmailRouteName,
                (string) $user->getId(), // Приводим к строке для совместимости
                $email,
                ['id' => (string) $user->getId()]
            );

            // Создание письма
            $emailMessage = (new Email())
                ->from(new Address($this->senderEmail, 'Neurify'))
                ->to($email)
                ->subject('Please Confirm Your Email')
                ->html('<p>To confirm your email, please click <a href="' . $signatureComponents->getSignedUrl() . '">here</a></p>');

            // Отправка письма
            $this->mailer->send($emailMessage);

            return true;
        } catch (TransportExceptionInterface $e) {
            // Логируем ошибку
            $this->logger->error('Failed to send email: ' . $e->getMessage(), [
                'email' => $email,
                'userId' => $user->getId(),
            ]);

            return false;
        }
    }

    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        try {
            // Validate email confirmation
            $this->verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                (string) $user->getId(), // Приводим к строке
                $user->getEmail()
            );

            // Update user verification status
            if (method_exists($user, 'setIsVerified')) {
                $user->setIsVerified(true);
            } else {
                throw new \RuntimeException('User entity does not have a setIsVerified method.');
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            // Log the validation error
            $this->logger->error('Email confirmation validation failed: ' . $e->getMessage(), [
                'userId' => $user->getId(),
                'email' => $user->getEmail(),
            ]);

            // Throw exception for higher-level handling
            throw new \RuntimeException('Email confirmation failed: ' . $e->getMessage(), 0, $e);
        }
    }
}

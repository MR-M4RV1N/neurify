<?php

namespace App\Controller;

use App\Service\EmailVerifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class EmailVerificationController extends AbstractController
{
    private $emailVerifier;
    private $entityManager;

    public function __construct(EmailVerifierService $emailVerifier, EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            // Обновление поля isVerified
            $user = $this->getUser();

            $user->setIsVerified(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_cpanel_presentation');
    }

    /**
     * @Route("/verify/resend", name="app_resend_verification_email")
     */
    public function resendVerificationEmail(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        // Генерация нового токена подтверждения
        $user->setConfirmationToken(Uuid::v4()->toRfc4122());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Отправка письма с подтверждением
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $user->getEmail());

        $this->addFlash('success', 'A new verification email has been sent.');

        return $this->redirectToRoute('cpanel_instruction_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailVerifierService;
use App\Service\EventGreetingCreatorService;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\AppCustomAuthenticator;
use App\Data\DescriptionData;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    private $userAuthenticator;
    private $authenticator;

    public function __construct(
        EmailVerifierService $emailVerifier,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator
    ) {
        $this->emailVerifier = $emailVerifier;
        $this->userAuthenticator = $userAuthenticator;
        $this->authenticator = $authenticator;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserFactory $userFactory,
        EntityManagerInterface $entityManager,
        EventGreetingCreatorService $eventGreetingCreatorService,
        TranslatorInterface $translator
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Honeypot check
            if ($form->get('city')->getData()) {
                // If the hidden field is filled, it's likely a bot.
                // We return success to confuse the bot, but do nothing.
                $this->addFlash('success', $translator->trans('registration.success'));
                return $this->redirectToRoute('app_login');
            }


            try {
                // Process user registration using Factory
                $userFactory->processRegistration(
                    $user,
                    $form->get('plainPassword')->getData()
                );

                // Save the user temporarily to generate an ID
                $entityManager->persist($user);
                $entityManager->flush();

                // Try sending the email confirmation
                $emailSent = $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $user->getEmail());

                if (!$emailSent) {
                    // Remove the user from the database if email sending fails
                    $entityManager->remove($user);
                    $entityManager->flush();

                    // Add flash message and redirect
                    $this->addFlash('error', $translator->trans('registration.email_error'));
                    return $this->redirectToRoute('app_register');
                }

                // Authenticate the user
                $this->userAuthenticator->authenticateUser(
                    $user,
                    $this->authenticator,
                    $request
                );

                //$request->getSession()->set('user_just_registered', true);
                $this->addFlash('success', $translator->trans('registration.success'));

                $eventGreetingCreatorService->createWelcomeEvent($user);

                // Redirect new users to the download page
                return $this->redirectToRoute('app_cpanel_download');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', $translator->trans('registration.error.email_exists'));
                return $this->redirectToRoute('app_register');
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', $translator->trans('registration.email_error'));
                // Consider logging the error here
                return $this->redirectToRoute('app_register');
            } catch (\Exception $e) {
                $this->addFlash('error', $translator->trans('registration.error.generic'));
                // Consider logging the error here
                return $this->redirectToRoute('app_register');
            }
        }

        // If form is not submitted or not valid
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check-username", name="check_username")
     */
    public function checkUsername(Request $request, UserRepository $userRepository): JsonResponse
    {
        $username = $request->query->get('username');
        $user = $userRepository->findOneBy(['username' => $username]);

        return new JsonResponse(['exists' => $user !== null]);
    }

    /**
     * @Route("/check-email", name="check_email", methods={"GET"})
     */
    public function checkEmail(Request $request, UserRepository $userRepository): JsonResponse
    {
        $email = $request->query->get('email');
        $exists = $userRepository->findOneBy(['email' => $email]) !== null;
        return new JsonResponse(['exists' => $exists]);
    }
}

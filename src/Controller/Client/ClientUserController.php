<?php

namespace App\Controller\Client;

use App\Entity\Event;
use App\Entity\Item;
use App\Entity\Mail;
use App\Entity\Map;
use App\Entity\Testimonial;
use App\Entity\User;
use App\Entity\Wording;
use App\Entity\WordingBenefit;
use App\Entity\WordingFeatures;
use App\Repository\EventRepository;
use App\Repository\LevelRepository;
use App\Repository\MapRepository;
use App\Repository\UserRepository;
use App\Repository\WordingBenefitRepository;
use App\Repository\WordingRepository;
use App\Service\ArrayFromItemsService;
use App\Service\PaginatorConfig;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ClientUserController extends AbstractController
{
    /**
     * @Route("/@{username}", requirements={"username"="[a-zA-Z0-9_\-]+"}, name="client_user_show", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine, LevelRepository $levelRepository, $username): Response
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(['username' => $username]);
        // Проверяем, что пользователь существует
        if (!$user) {
            throw $this->createNotFoundException('The user does not exist');
        }

        $level = ceil($user->getProgress() / 10);

        return $this->renderForm('client/users/show_user.html.twig', [
            'profile' => $user,
            'events' => $doctrine->getRepository(Event::class)->findBy(
                ['user' => $user, 'public' => true],
                ['id' => 'DESC']
            ),
            'level' => $levelRepository->findOneBy(['lang' => $user->getLang(), 'number' => $level == 0 ? 1 : $level]),
        ]);
    }

    /**
     * @Route("/event/{id}", requirements={"id"="\d+"}, name="client_event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('client/users/show_event.html.twig', [
            'event' => $event,
        ]);
    }
}

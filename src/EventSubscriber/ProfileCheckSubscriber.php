<?php

namespace App\EventSubscriber;

use App\Service\ProfileService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class ProfileCheckSubscriber implements EventSubscriberInterface
{
    private $profileService;
    private $router;
    private $security;

    public function __construct(ProfileService $profileService, RouterInterface $router, Security $security)
    {
        $this->profileService = $profileService;
        $this->router = $router;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
//        return [
//            KernelEvents::CONTROLLER => 'onKernelController',
//        ];
        return [];
    }

    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $user = $this->security->getUser(); // Используем сервис Security для получения текущего пользователя
        // Проверяем путь
        $path = $request->getPathInfo();
        if (preg_match('#/(users|public|ensemble|chat)#', $path)) {

            // Проверяем, заполнен ли профиль
            if (!$this->profileService->checkProfileCompletion($user) || $user->getType() == 0) {

                // Если профиль не заполнен, перенаправляем на страницу редактирования профиля или на другую страницу
                $response = new RedirectResponse($this->router->generate('app_cpanel_addition'));
                $event->setController(function() use ($response) {
                    return $response;
                });
            }
        }
    }
}
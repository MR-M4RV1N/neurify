<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $security;
    private $translator;
    private $defaultLocale;

    public function __construct(Security $security, TranslatorInterface $translator, string $defaultLocale = 'en')
    {
        $this->security = $security;
        $this->translator = $translator;
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // Не обрабатываем подзапросы
            return;
        }

        $request = $event->getRequest();
        $localeFromURL = $request->attributes->get('_locale');

        // Пытаемся определить локаль пользователя на основе его настроек в БД
        $user = $this->security->getUser();
        if ($user && method_exists($user, 'getLang')) {
            $locale = $user->getLang();
            // Переопределяем локаль из URL, если пользователь залогинен и у него установлен язык
            $request->setLocale($locale);
        } else if ($localeFromURL) {
            // Используем локаль из URL, если у пользователя нет выбранного языка
            $locale = $localeFromURL;
        } else {
            // Используем локаль по умолчанию, если ничего не установлено
            $locale = $request->getSession()->get('_locale', $this->defaultLocale);
        }

        $request->setLocale($locale);
        $this->translator->setLocale($locale);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [['onKernelController', 20]],
        ];
    }
}

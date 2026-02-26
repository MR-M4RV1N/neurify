<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EventGreetingCreatorService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createWelcomeEvent(User $user): void
    {
        $lang = $user->getLang(); // предполагается, что у пользователя есть getLang()

        $titleByLang = [
            'ru' => 'Регистрация на платформе Neurify.life',
            'lv' => 'Reģistrācija platformā Neurify.life',
            'en' => 'Registration on Neurify.life platform',
        ];

        $descriptionByLang = [
            'ru' => 'Первый маленький шаг к яркой и насыщенной жизни',
            'lv' => 'Pirmais mazais solis pretī spilgtai un piepildītai dzīvei',
            'en' => 'The first small step toward a bright and fulfilling life',
        ];

        $event = new Event();
        $event->setUser($user);
        $event->setTitle($titleByLang[$lang] ?? $titleByLang['en']);
        $event->setDescription($descriptionByLang[$lang] ?? $descriptionByLang['en']);
        $event->setDate(new \DateTime());
        $event->setTime(new \DateTime());
        $event->setPublic(true);
        $event->setLang($lang ?? 'en');
        $event->setPortfolio(false);
        $event->setPower(0);
        $event->setType(1);
        $event->setPriority(0);
        $event->setTask(false);

        $this->em->persist($event);
        $this->em->flush();
    }
}
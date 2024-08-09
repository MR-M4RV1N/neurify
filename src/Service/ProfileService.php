<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileService
{
    private $flashBag;
    private $translator;

    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    public function checkProfileCompletion($user)
    {
        // Если пользователь не аутентифицирован, возвращаем false
        if ($user === null) {
            return false;
        }
        // Выбираем поля, которые нужно проверить на заполненность
        $fieldsToCheck = ['getDescription', 'getLocation', 'getAge', 'getImage'];
        $emptyFields = [];
        // Проверяем, заполнены ли все поля профиля
        foreach ($fieldsToCheck as $field) {
            if (empty($user->$field())) {
                $emptyFields[] = $field;
            }
        }
        // Если какие-то поля не заполнены, добавляем сообщение об ошибке
        if (!empty($emptyFields)) {
            $this->flashBag->add('error', $this->translator->trans('You must fill in the all fields in your profile.'));
            return false;
        }

        return true;
    }
}
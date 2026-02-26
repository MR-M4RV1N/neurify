<?php

// src/Validator/UniqueUsernameValidator.php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UniqueUsernameValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $value]);

        // Получаем текущего пользователя через контекст
        $currentUser = $this->context->getRoot()->getData();

        // Проверка: если найденный пользователь не является текущим пользователем (например, при редактировании)
        if ($existingUser && $existingUser->getId() !== $currentUser->getId()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}

<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UniqueEmailValidator extends ConstraintValidator
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new \InvalidArgumentException(sprintf('Expected constraint of type %s, got %s', UniqueEmail::class, get_class($constraint)));
        }

        $currentUser = $this->security->getUser();
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $value]);

        if ($user && $user->getId() !== $currentUser->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}

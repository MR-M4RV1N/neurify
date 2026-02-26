<?php

namespace App\Factory;

use App\Data\DescriptionData;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFactory
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function processRegistration(User $user, string $plainPassword): void
    {
        $user->setImage('default.jpg');

        // Hash the password
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );

        // Set default user parameters
        $user->setRoles(['ROLE_USER']);
        $user->setProgress(User::PROGRESS_INITIAL);
        $user->setType(User::TYPE_PARTICIPANT);

        // Use language to set description and artisan title
        $lang = $user->getLang();
        $user->setDescription(DescriptionData::getDescription($lang));
        $user->setArtisan(DescriptionData::getArtisan($lang));

        // Security tokens
        $user->setConfirmationToken(Uuid::v4()->toRfc4122());
        $user->setIsVerified(false);
    }
}

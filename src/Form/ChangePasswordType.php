<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Текущий пароль',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Введите текущий пароль']),
                    new UserPassword(['message' => 'Текущий пароль неверный']),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'Новый пароль',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Введите новый пароль']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Новый пароль должен содержать минимум {{ limit }} символов',
                    ]),
                ],
            ]);
    }
}
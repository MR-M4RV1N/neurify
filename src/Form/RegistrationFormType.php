<?php

namespace App\Form;

use App\Data\Countries;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.email.not_blank',
                    ]),
                    new Email([
                        'message' => 'registration.email.invalid',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Login',
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.username.not_blank',
                    ]),
                ],
            ])
            // Honeypot field (hidden from users, visible to bots)
            ->add('city', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'City', // Label needed for bots to think it's real
                'attr' => ['style' => 'display:none !important', 'tabindex' => '-1', 'autocomplete' => 'off'],
                'label_attr' => ['style' => 'display:none !important'],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.firstname.not_blank',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.lastname.not_blank',
                    ]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices'  => Countries::getList(),
                'label' => 'Choose your country',
                'label_attr' => ['class' => 'form-label mt-4 mr-2'],
                'placeholder' => 'Select a country',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lang', ChoiceType::class, [
                'choices'  => [
                    'Russian' => 'ru',
                    'English' => 'en',
                    'Latvian' => 'lv',
                ],
                'label' => 'Choose your language',
                'label_attr' => ['class' => 'form-label mt-4 mr-2'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.lang.not_blank',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'registration.terms.is_true',
                    ]),
                    new NotBlank([
                        'message' => 'registration.terms.not_blank',
                    ]),
                ],
                'label_html' => true,
                'label' => 'I agree to the Terms of Use',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'registration.password.mismatch',
                'options' => ['attr' => ['class' => 'password-field', 'autocomplete' => 'new-password']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.password.not_blank',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'registration.password.min_length',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

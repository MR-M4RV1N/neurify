<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class UserAdditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'Placeholder' => 'Latvia, Rīga',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Choose file',
                'label_attr' => ['class' => 'form-label mt-3'],
                'mapped' => false, // если в вашей сущности нет свойства 'file'
                'required' => false, // делаем загрузку файла необязательной
                'attr' => ['class' => 'form-control-file'], // Bootstrap 4 класс для стилизации
            ])
            ->add('lang', ChoiceType::class, [
                'choices'  => [
                    'Latvian' => 'lv',
                    'Russian' => 'ru',
                    'English' => 'en',
                ],
                'label' => 'Choose your language', // Метка поля
                'label_attr' => ['class' => 'form-label mt-4 mr-2'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Describe yourself here',
                ],
                'label' => 'About me',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/[\[\]]/',
                        'match' => false,
                        'message' => 'Your description should not contain [ or ]',
                    ]),
                ],
                'help' => 'Your description should not contain [ or ]', // Добавление подсказки help
                'help_attr' => ['class' => 'form-text text-muted small'], // Дополнительные атрибуты для тега help
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Progress;
use App\Entity\Draft;
use App\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Получаем пользователя из опций

        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Title',
                'label_attr' => ['class' => 'form-label'],
                'help' => 'New, unusual, scary, difficult.', // Добавление подсказки help
                'help_attr' => ['class' => 'form-text text-muted small'], // Дополнительные атрибуты для тега help
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label'],
                'help' => 'Explain why it is worth completing this challenge.', // Добавление подсказки help
                'help_attr' => ['class' => 'form-text text-muted small'], // Дополнительные атрибуты для тега help
            ])
            ->add('image', FileType::class, [
                'label' => 'Choose file',
                'label_attr' => ['class' => 'form-label mt-3'],
                'mapped' => false, // если в вашей сущности нет свойства 'file'
                'required' => false, // делаем загрузку файла необязательной
                'attr' => ['class' => 'form-control-file'], // Bootstrap 4 класс для стилизации
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Draft::class,
        ]);

        $resolver->setRequired('user');
    }
}

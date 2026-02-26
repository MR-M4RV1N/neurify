<?php

namespace App\Form;

use App\Entity\Ensemble;
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

class EnsembleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Получаем пользователя из опций
        $ensemble = $options['data'];

        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Title',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label mt-2'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Choose file',
                'label_attr' => ['class' => 'form-label mt-3'],
                'mapped' => false, // если в вашей сущности нет свойства 'file'
                'required' => false, // делаем загрузку файла необязательной
                'attr' => ['class' => 'form-control-file'], // Bootstrap 4 класс для стилизации
            ])
            ->add('is_closed', ChoiceType::class, [
                'choices' => [
                    'Opened' => false,
                    'Closed' => true,
                ],
                'label' => 'Status',
                'label_attr' => ['class' => 'form-label mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('selected', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
                'label' => 'Task list',
                'label_attr' => ['class' => 'form-label mt-3'],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'selectedChoice',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ensemble::class,
        ]);

        $resolver->setRequired('user');
    }
}

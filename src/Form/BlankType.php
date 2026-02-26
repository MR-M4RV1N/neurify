<?php

namespace App\Form;


use App\Entity\Blank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Title',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Universal' => 1,
                    'Local' => 2,
                ],
                'label' => 'Choose a category', // Необязательно: задайте метку для поля
                'label_attr' => ['class' => 'form-label mt-3'],
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Choose a file',
                'mapped' => false, // если в вашей сущности нет свойства 'file'
                'required' => false, // делаем загрузку файла необязательной
                'attr' => ['class' => 'form-control-file'], // Bootstrap 4 класс для стилизации
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blank::class,
        ]);
    }
}

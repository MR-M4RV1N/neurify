<?php

namespace App\Form;

use App\Entity\Journal; // замени на свою сущность
use App\Form\DataTransformer\HTMLPurifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class JournalType extends AbstractType
{
    private $htmlPurifierTransformer;

    public function __construct(HTMLPurifierTransformer $htmlPurifierTransformer)
    {
        $this->htmlPurifierTransformer = $htmlPurifierTransformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('text', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Text',
                'label_attr' => ['class' => 'form-label mt-3'],
                'help' => 'Describe how your day went',
                'help_attr' => ['class' => 'form-text text-muted small'],
            ])
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Achievement title',
                'label_attr' => ['class' => 'form-label'],
                'help' => 'Write a title for your post', // Добавление подсказки help
                'help_attr' => ['class' => 'form-text text-muted small'], // Дополнительные атрибуты для тега help
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text', // позволяет выбирать дату через стандартный HTML5 <input type="date">
                'input'  => 'datetime_immutable',
                'attr' => ['class' => 'form-control'], // Bootstrap класс для стилизации
                'label' => 'Date',
                'label_attr' => ['class' => 'form-label'],
                // 'format' => 'yyyy-MM-dd', // необязательно, если используете widget 'single_text'
                'required' => true,
            ])
            ->get('text') // Получаем поле
            ->addModelTransformer($this->htmlPurifierTransformer) // Применяем трансформер
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Journal::class,
        ]);

        $resolver->setRequired('user');
    }
}
<?php

namespace App\Form;

use App\Entity\Progress;
use App\Entity\Event;
use App\Form\DataTransformer\HTMLPurifierTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    private $htmlPurifierTransformer;

    public function __construct(HTMLPurifierTransformer $htmlPurifierTransformer)
    {
        $this->htmlPurifierTransformer = $htmlPurifierTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['user']) {
            throw new \InvalidArgumentException('The "user" option is required.');
        }
        $user = $options['user']; // Получаем пользователя из опций

        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Event title',
                'label_attr' => ['class' => 'form-label'],
                'help' => 'Bright event, task, challenge, new experience, achievement', // Добавление подсказки help
                'help_attr' => ['class' => 'form-text text-muted small'], // Дополнительные атрибуты для тега help
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text', // позволяет выбирать дату через стандартный HTML5 <input type="date">
                'attr' => ['class' => 'form-control'], // Bootstrap класс для стилизации
                'label' => 'Date of event',
                'label_attr' => ['class' => 'form-label'],
                // 'format' => 'yyyy-MM-dd', // необязательно, если используете widget 'single_text'
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label mt-3'],
                'help' => 'Describe your quest in more detail',
                'help_attr' => ['class' => 'form-text text-muted small'],
            ])
            ->add('instruction', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Instruction',
                'label_attr' => ['class' => 'form-label mt-3'],
                'help' => 'Describe your quest in more detail',
                'help_attr' => ['class' => 'form-text text-muted small'],
            ])
            ->add('instructionFileUpload', FileType::class, [
                'label' => 'Upload instruction file',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'text/plain',
                        ],
                        'mimeTypesMessage' => 'Загрузите файл в формате PDF, PowerPoint, Word или TXT',
                    ])
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Choose file',
                'mapped' => false, // если в вашей сущности нет свойства 'file'
                'required' => false, // делаем загрузку файла необязательной
                'attr' => ['class' => 'form-control-file'], // Bootstrap 4 класс для стилизации
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('ensemble', EntityType::class, [
                'class' => 'App\Entity\Ensemble',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('e')
                        ->where('e.author = :user')
                        ->setParameter('user', $user);
                },
                'choice_label' => 'title',
                'attr' => ['class' => 'form-control'],
                'label' => 'Category',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'placeholder' => 'Default',
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'Priority',
                'label_attr' => ['class' => 'form-label mt-3'],
                'attr' => [
                    'class' => 'form-control',
                    'min'   => 0,   // поменяй диапазон при необходимости
                    'max'   => 100,
                    'step'  => 1,
                    'placeholder' => 'Enter priority',
                ],
                'required' => false,
                'empty_data' => '0',
                'constraints' => [
                    new Assert\Type(['type' => 'integer', 'message' => 'Priority must be an integer.']),
                    new Assert\Range([
                        'min' => 0,
                        'max' => 100,
                        'notInRangeMessage' => 'Priority must be between {{ min }} and {{ max }}.',
                    ]),
                ],
            ])
            // Добавляем поле Публичная запись
            ->add('public', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'attr' => ['class' => 'form-select ml-1'],
                'label' => 'Public record',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
            ])
            // Добавляем поле Закреплено
            ->add('pinned', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'attr' => ['class' => 'form-select ml-1'],
                'label' => 'Featured record',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
            ])
            ->add('portfolio', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
                'attr' => ['class' => 'form-select ml-1'],
                'label' => 'Portfolio',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
                'data' => false,
            ])
            ->add('task', ChoiceType::class, [
                'choices' => [
                    'No' => false,
                    'Yes' => true,
                ],
                'attr' => ['class' => 'form-select ml-1'],
                'label' => 'Task',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Memorable event' => 1,
                    'Challenge'  => 4,
                    'Achievement'     => 2,
                    'New experience'  => 3,
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Type',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('power', ChoiceType::class, [
                'choices' => [
                    '0' => 0,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Power',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ])
            ->get('description') // Получаем поле
            ->addModelTransformer($this->htmlPurifierTransformer) // Применяем трансформер
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);

        $resolver->setRequired('user');
    }
}

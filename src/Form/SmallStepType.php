<?php

namespace App\Form;

use App\Entity\LifeWheelSector;
use App\Entity\SmallStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class SmallStepType extends AbstractType
{
    /** @var TranslatorInterface */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'trim' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Title cannot be empty.']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Title is too short.',
                        'maxMessage' => 'Title is too long.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => 255,
                    'placeholder' => 'Например: Обновить резюме на 1 пункт',
                ],
                'label' => 'Title',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('category', EntityType::class, [
                'class' => LifeWheelSector::class,
                'choice_label' => function (LifeWheelSector $sector) {
                    return $this->translator->trans($sector->getName());
                },
                'choice_translation_domain' => false, // важно, чтобы не было "двойного перевода"
                'placeholder' => 'Выбери категорию',
                'required' => false, // поставь true, если категория обязательна
                'attr' => ['class' => 'form-control'],
                'label' => 'Category',
                'label_attr' => ['class' => 'form-label'],
                // если нужно сортировать:
                // 'query_builder' => function (LifeWheelSectorsRepository $r) {
                //     return $r->createQueryBuilder('s')->orderBy('s.sort', 'ASC');
                // },
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 5000,
                        'maxMessage' => 'Description is too long.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Опиши шаг подробнее (необязательно)',
                ],
                'label' => 'Description',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('draft', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Small step' => false,
                    'Idea' => true,
                ],
                'placeholder' => false,
                'required' => true,
                'expanded' => false, // dropdown
                'multiple' => false,
                'attr' => ['class' => 'form-control'],

                // ВАЖНО: для корректной работы boolean в HTML <select>
                'choice_value' => function ($choice) {
                    if ($choice === null) {
                        return '';
                    }
                    return $choice ? '1' : '0';
                },
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr'   => ['class' => 'form-control'],
                'label'  => 'Date',
                'label_attr' => ['class' => 'form-label'],
                'required' => true,
                // 'data' не ставим тут, чтобы не перетирать при edit
            ])
        ;

        // Дата "сегодня" только для нового объекта
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var SmallStep|null $step */
            $step = $event->getData();
            if (!$step) {
                return;
            }

            if (null === $step->getDate()) {
                $step->setDate(new \DateTimeImmutable('today'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SmallStep::class,
        ]);
    }
}
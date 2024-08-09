<?php

namespace App\Form;

use App\Entity\Progress;
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

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Получаем пользователя из опций

        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Title',
                'label_attr' => ['class' => 'form-label'],
                'help' => 'Only the new and unusual. Something that hasn\'t been done before.', // Добавление подсказки help
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
                'label_attr' => ['class' => 'form-label mt-2'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Choose file',
                'mapped' => false, // если в вашей сущности нет свойства 'file'
                'required' => false, // делаем загрузку файла необязательной
                'attr' => ['class' => 'form-control-file'], // Bootstrap 4 класс для стилизации
                'label_attr' => ['class' => 'form-label mt-3'],
            ])
            ->add('ensemble', EntityType::class, [
                'class' => 'App\Entity\Ensemble',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('e')
                        ->join('App\Entity\EnsembleParticipant', 'ep', 'WITH', 'e.id = ep.ensemble') // Присоединяем EnsembleParticipant к Ensemble
                        ->where('ep.user = :user') // Фильтруем по пользователю
                        ->setParameter('user', $user);
                },
                'choice_label' => 'title',
                'attr' => ['class' => 'form-control'],
                'label' => 'Group',
                'label_attr' => ['class' => 'form-label mt-3'],
                'required' => false,
                'placeholder' => 'Default',
            ])
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

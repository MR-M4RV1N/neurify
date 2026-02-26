<?php

namespace App\Form;

use App\Entity\Ensemble;
use App\Entity\Picture;
use App\Entity\User;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['user']) {
            throw new \InvalidArgumentException('The "user" option is required.');
        }
        $user = $options['user']; // Получаем пользователя из опций

        $builder
            ->add('images', CollectionType::class, [
                'entry_type' => FileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'Заголовок',
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
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'placeholder' => 'Default',
            ])
            ->add('public', CheckboxType::class, [
                'label'    => 'Публично',
                'required' => false,
            ])
            ->add('portfolio', CheckboxType::class, [
                'label'    => 'Display in portfolio',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);

        $resolver->setRequired('user');
    }
}
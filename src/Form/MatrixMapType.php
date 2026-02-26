<?php

namespace App\Form;

use App\Entity\MatrixMap;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatrixMapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('public', CheckboxType::class, ['required' => false])
            ->add('filename', TextType::class, ['required' => false])
            ->add('ensemble', EntityType::class, [
                'class' => 'App\Entity\Ensemble',
                'choice_label' => 'name',
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'email',
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
            'data_class' => MatrixMap::class,
        ]);
    }
}
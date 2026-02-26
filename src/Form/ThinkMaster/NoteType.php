<?php

namespace App\Form\ThinkMaster;

use App\Entity\ThinkMaster\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class)
            ->add('next', TextareaType::class, [
                'required' => false,
                'label' => 'Next step'
            ])
            ->add('recommendations', TextareaType::class, [
                'required' => false,
                'label' => 'Recommendations for AI',
                'attr' => ['rows' => 6]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
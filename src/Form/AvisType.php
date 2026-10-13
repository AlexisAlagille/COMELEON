<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Range;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', IntegerType::class, [
                'label' => 'Note',
                'constraints' => [
                    new Range(
                        min: 0,
                        max: 5,
                        notInRangeMessage: 'La note doit être comprise entre {{ min }} et {{ max }}.'
                    ),
                ],
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                ],
            ])
            ->add('commentaire', TextareaType::class, ['label' => 'Commentaire',
            'attr' => [
                    'rows' => 5,
                    'maxlength' => 255,
                ],]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}

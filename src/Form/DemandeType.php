<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Prestation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prestation', EntityType::class, [
                'class' => Prestation::class,
                'choice_label' => 'titre',
                'label' => 'Prestation souhaitée',
            ])

            ->add('date', DateTimeType::class, [
                'label' => 'Date souhaitée',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Décrivez votre besoin',
                'attr' => [
                    'rows' => 5,
                    'maxlength' => 255,
                ],
            ])

            ->add('details', TextareaType::class, [
                'label' => 'Précisions complémentaires',
                'required' => false,
                'attr' => [
                    'rows' => 3,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
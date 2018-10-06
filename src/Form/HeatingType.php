<?php

namespace App\Form;

use App\Entity\ExtractionType;
use App\Entity\Heating;
use App\Entity\HeatingSource;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation',null,['label' => 'Désignation du produit'])
            ->add('onTheGround',CheckboxType::class,['label' => 'Posé au sol?'])
            ->add('heatingType', EntityType::class, [
                'label' => 'Type de chauffage',
                'class' => \App\Entity\HeatingType::class,
                'choice_label' => 'name',
            ])
            ->add('sourceType', EntityType::class, [
                'label' => 'Source d\'energie',
                'class' => HeatingSource::class,
                'choice_label' => 'name',
            ])
            ->add('extractionType', EntityType::class, [
                'label' => 'Type d\'extraction',
                'class' => ExtractionType::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Heating::class,
        ]);
    }
}

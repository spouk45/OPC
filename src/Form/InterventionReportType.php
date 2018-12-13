<?php

namespace App\Form;

use App\Entity\InterventionReport;
use App\Entity\TypeInterventionReport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plannedOrRealised', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Plannifié' => false,
                    'Réalisé' => true,
                ],
                'expanded' => true,
                'multiple' => false,
                'required'=>true,
                'data' => false,
            ])
            ->add('dateForSelected', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false,
                'required' => true,
                ])
//            ->add('plannedDate', DateType::class, [
//                'label' => 'Date de plannification',
//                'widget' => 'single_text',
//                'required' => false,
//            ])
//            ->add('realisedDate', DateType::class, [
//                'label' => 'Date de realisation de l\'intervention',
//                'widget' => 'single_text',
//                'required' => false,])
            ->add('comment')
            ->add('typeInterventionReport', EntityType::class, [
                'label' => 'Type d\'intervention',
                'class' => TypeInterventionReport::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'text-capitalize'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterventionReport::class,
        ]);
    }
}

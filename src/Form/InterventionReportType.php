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
                'mapped' => false,
                'required' => true,
                ])
            ->add('comment')
//            ->add('typeInterventionReport', EntityType::class, [
//                'label' => 'Type d\'intervention',
//                'class' => TypeInterventionReport::class,
//                'choice_label' => 'name',
//                'attr' => ['class' => 'text-capitalize'],
//            ])
            ->add('typeInterventionReport',ChoiceType::class,[
                'choices' => InterventionReport::CHOICE,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterventionReport::class,
        ]);
    }
}

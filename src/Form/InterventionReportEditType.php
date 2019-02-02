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

class InterventionReportEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plannedDate', DateType::class, [
                'label' => 'Plannifier le :',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('realisedDate', DateType::class, [
                'label' => 'Réalisé le :',
                'widget' => 'single_text',
                'required' => false,])
            ->add('comment')
//            ->add('typeInterventionReport', EntityType::class, [
//                'label' => 'Type d\'intervention',
//                'class' => TypeInterventionReport::class,
//                'choice_label' => 'name',
//                'attr' => ['class' => 'text-capitalize'],
//            ])
            ->add('typeInterventionReport',ChoiceType::class,[
                'choices' => InterventionReport::CHOICE,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InterventionReport::class,
        ]);
    }
}

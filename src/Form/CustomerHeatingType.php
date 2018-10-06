<?php

namespace App\Form;

use App\Entity\CustomerHeating;
use App\Entity\Heating;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerHeatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mark', TextType::class, ['label' => 'Marque'])
            ->add('model', TextType::class, ['label' => 'Model'])
            ->add('serial', TextType::class, ['label' => 'Numéro de série'])
            ->add('comment', TextareaType::class, ['label' => 'Commentaire'])
            ->add('contractDate', DateType::class, [
                'label' => 'Date de signature du contrat',
                'widget' => 'single_text'
            ])
            ->add('anniversaryDate', BirthdayType::class, [
                'label' => 'Date d\'anniversaire de l\'entretien',
                'widget' => 'single_text',
            ])
//            ->add('lastMaintenanceDate',DateType::class,['label'=>'Commentaire'])
            ->add('contractFinish', CheckboxType::class, [
                'label' => 'Contrat Rompu ?'
            ])
//            ->add('customer',EntityType::class,['label'=>'Client'])
            ->add('heating', EntityType::class, [
                'label' => 'Produit',
                'class' => Heating::class,
                'choice_label' => 'designation',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomerHeating::class,
        ]);
    }
}

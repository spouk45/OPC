<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Heating;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('adress', TextareaType::class, ['label' => 'Adresse'])
            ->add('phone', TelType::class, ['label' => 'Téléphone'])
            ->add('phone2', TelType::class, ['label' => 'Téléphone 2', 'required' => false,])
            ->add('mail', EmailType::class, ['label' => 'Email'])
            ->add('information', TextareaType::class, ['label' => 'Complément d\'information'])
//            ->add('heating', EntityType::class, [
//                'class' => Heating::class,
//                'label' => 'Chauffage',
//                'choice_label' => 'name',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}

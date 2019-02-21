<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('complementAdress', TextareaType::class, ['label' => 'N° et rue'])
            ->add('phone', TelType::class, ['label' => 'Téléphone'])
            ->add('phone2', TelType::class, ['label' => 'Téléphone 2', 'required' => false,])
            ->add('mail', EmailType::class, ['label' => 'Email'])
            ->add('information', TextareaType::class, ['label' => 'Complément d\'information','required' => false])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('postalCode', IntegerType::class, ['label' => 'Code Postal'])
            ->add('contractDate', DateType::class, [
                'label' => 'Date de signature du contrat',
                'widget' => 'single_text',
                'required' => false,
            ])
//            ->add('anniversaryDate', BirthdayType::class, [
//                'label' => 'Anniversaire de l\'entretien',
//                'widget' => 'single_text',
//                'required' => false,
//            ])
            ->add('anniversaryDate',ChoiceType::class,[
                'label' => 'Date d\'anniversaire',
                'choices' => [
                    'Janvier' => 1,
                    'Février' => 2,
                    'Mars' => 3,
                    'Avril' => 4,
                    'Mai' => 5,
                    'Juin' => 6,
                    'Juillet' => 7,
                    'Août' => 8,
                    'Septembre' => 9,
                    'Octobre' => 10,
                    'Novembre' => 11,
                    'Décembre' => 12,
                ]
            ])
            ->add('lastMaintenanceDate', DateType::class, [
                'label' => 'Date de dernier entretien',
                'widget' => 'single_text',
                'required' => false,
            ])
//            ->add('lastMaintenanceDate',DateType::class,['label'=>'Commentaire'])
            ->add('contractFinish', CheckboxType::class, [
                'label' => 'Contrat Rompu ?',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}

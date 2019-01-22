<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName',null,['label' => 'Nom Prénonm'])
            ->add('username',null,['label' => 'Nom d\'utilisateur'])
            ->add('email',EmailType::class,['label' => 'E-mail'])
//            ->add('password',PasswordType::class,['label' => 'Mot de passe','mapped' => false])
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Mot de passe encore'],
                'invalid_message' => 'Les mots de passes sont différents.',
            ])
//            ->add('roles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

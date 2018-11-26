<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('nom',      TextType::class)
            ->add('pnom',     TextType::class)
            ->add('email',    EmailType::class)
            ->add('username', TextType::class)
            ->add('password', RepeatedType::class, array(
                'type' =>     PasswordType::class,
                'first_options'  => array('label' => 'Mot de pass'),
                'second_options' => array('label' => 'Répéter le mot de passe'),
            ))
        ;
        // $builder->add('pnomUser', TextType::class);
    }

    public function getNom()
    {
        return 'user_registration';
    }

    // public function getPnomUser()
    // {
    //     return 'user_registration';
    // }
}

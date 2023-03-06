<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Contact;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'padding:18px;border: 2px solid lightgrey;',
                    'placeholder' => 'Votre nom complet'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre nom complet ne peut pas être vide'
                    ]),
                ],],)
            ->add('phone', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'padding:18px;border: 2px solid lightgrey;',
                    'placeholder' => 'Votre téléphone'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le téléphone ne peut pas être vide'
                    ]),
                ],],)
            ->add('email', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'padding:18px;border: 2px solid lightgrey;',
                    'placeholder' => 'Votre e-mail'
                ],])
            ->add('message', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'padding:18px;border: 2px solid lightgrey;',
                    'placeholder' => 'Message'
                ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

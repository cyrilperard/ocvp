<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre prénom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prenom ne peut pas être vide'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z-]+$/i',
                        'htmlPattern' => '[a-zA-Z-]+',
                        'message' => 'Le prénom ne peut contenir que des caractères alphabétiqueset (-)'
                    ]),
                    new Length([
                        "min" => 2,
                        "max" => 20
                    ]),
                ],],)
            ->add('last_name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre nom'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z-]+$/i',
                        'htmlPattern' => '[a-zA-Z-]+',
                        'message' => 'Le nom ne peut contenir que des caractères alphabétiqueset (-)'
                    ]),
                    new Length([
                        "min" => 2,
                        "max" => 20
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre e-mail'
                ],])
            ->add('telephone', TelType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre téléphone'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide'
                    ]),
                    new Length([
                        "min" => 10,
                        "max" => 10
                    ]),
                    new Regex('#^0[1-9]{1}\d{8}$#')
                ],])
            ->add('mobile', TelType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre mobile'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide'
                    ]),
                    new Length([
                        "min" => 10,
                        "max" => 10
                    ]),
                    new Regex('#^0[6-7]{1}\d{8}$#')
                ],])
            ->add('birthday', DateType::class, [
                'html5' => true,
                'widget' => 'choice',
                'required' => true,
                'format' => 'dd-MM-yyyy',
                'years' => range(1950, \date("Y"), 1),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

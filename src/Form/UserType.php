<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Prénom de l'utilisateur"
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
                    'placeholder' => "Nom de l'utilisateur"
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
                    'placeholder' => "E-mail de l'utilisateur"
                ],])
            ->add('telephone', TelType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Téléphone de l'utilisateur"
                ],
                'constraints' => [
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
                    'placeholder' => "Mobile de l'utilisateur"
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

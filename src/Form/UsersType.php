<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class, [
            'required' => true,
            'label' => 'Prénom',
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a mb-3 '
            ],
            'label_attr' => [
                'class' => 'policeForm'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Le prénom est obligatoire'
                ])
            ]
        ])
        ->add('lastname', TextType::class, [
            'required' => true,
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a mb-3'
            ],
            'label_attr' => [
                'class' => 'policeForm'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Le nom est obligatoire'
                ])
            ]
        ])
        ->add('email', EmailType::class, [
            'required' => true,
            'label' => 'Adresse e-mail',
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a mb-3'
            ],
            'label_attr' => [
                'class' => 'policeForm'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'L\adresse e-mail est obligatoire'
                ]),
                new Email([
                    'message' => 'Votre adresse e-mail est invalide'
                ])
            ]
        ])
        ->add('phone', TelType::class, [
            'required' => true,
            'label' => 'Télephone',
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a mb-3',
            ],
            'label_attr' => [
                'class' => 'policeForm'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Le numéro de télephone est obligatoire'
                ])
            ]
        ])
        ->add('plainPassword', RepeatedType::class, [
            'required' => true,
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passes ne sont pas identiques !',
            'first_options' => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Confirmer le mot de passe'],
            'mapped' => false,
            'options' => [
                'attr' =>[
                     'class' => 'form-control form-control-lg form-control-a mb-3'
                ],
                'label_attr' => [
                'class' => 'policeForm'
            ],
            ],
            
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrez votre mot de passe',
                ]),
                new Length([
                    'min' => 10,
                    'minMessage' => 'Votre mot de passe doit contenir minimum {{ limit }} caractères',
                    'max' => 4096,
                ]),
                
            ],
        ])
        ->add('roles', ChoiceType::class, [
            'required' => true,
            'label' => 'Rôle utilisateur',
            'expanded' => true,
            'multiple' => true,
            'choices' => [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN'
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez choisir un rôle pour l\'utilisateur'
                ])
            ]
        ])
        ->add('compagny', TextType::class,[
            'required'=>false,
            'label'=>'Ajouter une entreprise avec laquelle vous êtes affilié : ',
            'mapped' => false,
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a mb-3 '
            ],
            'label_attr' => [
                'class' => 'policeForm'
            ],
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}

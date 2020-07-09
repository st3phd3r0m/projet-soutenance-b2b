<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class RegistrationFormType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les CGU ',
                'label_attr' => [
                    'class' => 'policeForm mr-2'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes.',
                    ]),
                ],
            ])
            // ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}

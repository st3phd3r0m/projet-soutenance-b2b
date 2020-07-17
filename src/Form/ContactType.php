<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
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
            ->add('message', TextareaType::class, [
                'required'=>true,
                'label'=>'Message',
                'attr' => [
                    'class' => ' form-control-lg form-control-a mb-3 d-block w-100 ',
                ],
                'label_attr' => [
                    'class' => 'policeForm'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le contenu de votre annonce',
                        
                    ]),
                    new Length([
                        'min' => 20,
                        'minMessage' => "Le texte doit comporter au minimum {{ limit }}
                        caractères.",
                        
                    ])
                ] 
            ])
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

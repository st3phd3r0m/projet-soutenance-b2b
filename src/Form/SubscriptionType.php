<?php

namespace App\Form;

use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'label'=>'Le nom du pack',
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a mb-3 '
            ],
            'label_attr' => [
                'class' => 'policeForm'
            ],
            'constraints' => [
                new NotBlank([
                    'message'=>'Veuillez saisir un nom.',
                    'groups'=> ['new', 'update']
                ]),
                new Length([
                    'min' => 4,
                    'minMessage' => "Le nom doit comporter au minimum {{ limit }}
                    caractères.",
                    'groups'=> ['new', 'update']
                ])
            ]
        ])
        ->add('price', MoneyType::class, [
            'label'=>'prix en : ',
            'mapped' => true,
            'attr'=>[
                'class' => 'form-control form-control-lg form-control-a mb-3 ',

            ]
        ])
        ->add('credit', IntegerType::class, [
            'label'=>'Credits confèrés : ',
            'mapped' => true,
            'attr'=>[
                'class' => 'form-control form-control-lg form-control-a mb-3 ',

            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}

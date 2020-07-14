<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'label'=>'Le nom de la categorie',
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
                    'min' => 10,
                    'minMessage' => "Le nom doit comporter au minimum {{ limit }}
                    caractères.",
                    'groups'=> ['new', 'update']
                ])
            ]
        ])
            ->add('is_illimited')
            ->add('credits_to_unlock', IntegerType::class, [
                'label'=>'Cout d\'un débloquage en credits : ',
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
            'data_class' => Categories::class,
        ]);
    }
}

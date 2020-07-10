<?php

namespace App\Form;

use App\Entity\ActivitySector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ActivitySectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label'=>'Nom de la Categorie',
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez saisir un nom.',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => "Le nom doit comporter au minimum {{ limit }}
                        caractères.",
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivitySector::class,
        ]);
    }
}

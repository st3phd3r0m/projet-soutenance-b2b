<?php

namespace App\Form;

use App\Entity\Announcements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnouncementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label'=>'Le titre de votre annonce',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez saisir un titre de l\'annonce.',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => "Le titre doit comporter au minimum {{ limit }}
                        caractères.",
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'required'=>true,
                'label'=>'Le contenu de votre annonce',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir la description de l\'article',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => "Le texte doit comporter au minimum {{ limit }}
                        caractères.",
                        'groups'=> ['new', 'update']
                    ])
                ] 
            ])  


            ->add('description')
            ->add('image')
            ->add('notes')
            ->add('ref')
            ->add('key_words')
            ->add('city')
            ->add('postal_code')
            ->add('gps_location')
            ->add('created_at')
            ->add('price_range')
            ->add('urgency')
            ->add('slug')
            ->add('category')
            ->add('user')
            ->add('profession')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announcements::class,
        ]);
    }
}

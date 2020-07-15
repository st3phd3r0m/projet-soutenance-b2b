<?php

namespace App\Form;

use App\Entity\ActivitySector;
use App\Entity\Announcements;
use App\Entity\Categories;
use App\Entity\Professions;
use App\Repository\ProfessionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnnouncementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label'=>'Le titre',
                'attr' => [
                    'class' => 'form-control-lg form-control-a mb-3 d-block w-100 ',
                    'placeholder' => 'Titre de l\'annonce'
                ],
                'label_attr' => [
                    'class' => 'policeForm'
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
                // 'label'=>'Description de votre annonce',
                'attr' => [
                    'class' => ' form-control-lg form-control-a mb-3 d-block w-100 ',
                    'placeholder' => 'Description de votre annonce'
                ],
                'label_attr' => [
                    'class' => 'policeForm'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le contenu de votre annonce',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 20,
                        'minMessage' => "Le texte doit comporter au minimum {{ limit }}
                        caractères.",
                        'groups'=> ['new', 'update']
                    ])
                ] 
            ])  
            ->add('notes', TextareaType::class, [
                // 'required'=>true,
                'label'=>'Ajouter accessoirement une note pour votre annonce',
                'attr' => [
                    'class' => 'form-control form-control-lg form-control-a mb-3 ',
                    'placeholder'=>' notes'
                ],
               
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
            ]) 
            ->add('key_words', TextType::class,[
                'label'=>'Ajouter des mots-clés, délimités par des points-virgules (";"), afin de référencer votre annonce : ',
                'attr' => [
                    'class' => 'form-control form-control-lg form-control-a mb-3 '
                ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
                'mapped' => false,
            ])
            ->add('price_min', MoneyType::class, [
                'label'=>'entre : ',
                'mapped' => false,
                'attr'=>[
                    'value'=>'1000',
                    'class' => ' form-control-lg form-control-a mb-3 w-100 ',

                ],
                 'label_attr' => [
                    'class' => 'policeForm'
                ],
            ])
            ->add('price_max', MoneyType::class, [
                'label'=>'et : ',
                'mapped' => false,
                // 'attr'=>['value'=>'100000']
                'attr' => [
                    'class' => ' form-control-lg form-control-a mb-3 w-100',
                    'value'=>'100000'
                ],
                'label_attr' => [
                    'class' => 'policeForm'
                ],
            ])
            ->add('category', EntityType::class, [
                'label'=>'Choisir la catégorie',
                'class'=> Categories::class,
                'choice_label'=> 'name',
                'attr' => [
                    'class' => 'btn pinkBackground',
                    'placeholder'=> 'Choisir la categorie'
                ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
            ])
            ->add('activity_sector', EntityType::class, [
                'label'=>'Secteur d\'activité',
                'class'=> ActivitySector::class,
                'choice_label'=> 'name',
                // 'attr'=>['class'=>'actSect'] ICI A VOIR !!!
                'attr' => [
                    // 'class' => ' actSelect'
                    'class' => 'form-control-lg form-control-a mb-3 actSelect btn pinkBackground'
                ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label'=>'Ville d\'intervention',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control-lg form-control-a mb-3 ',
                    'list'=>'selectPannel',
                    'autocomplete'=>'off',
                    'placeholder' => 'ville d\'intervention'
                ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
            ])
            ->add('coordinates', HiddenType::class, [
                'required' => true,
                'mapped' => false,
                // 'attr' => [
                //     'class' => 'form-control form-control-lg form-control-a mb-3 '
                // ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
            ])
            ->add('urgency', ChoiceType::class, [
				'label' => 'Urgence de l\'annonce',
				'expanded' => true,
                'multiple' => false,
                // 'attr' => [
                //     'class' => 'form-control form-control-lg form-control-a mb-3 '
                // ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
				'choices' => [
					'Très urgent' => '3',
                    'Urgent' => '2',
                    'Normal' => '1'
				]
            ])
            ->add('imageFile', VichImageType::class,[
                'label'=>'Image d\'illustration de votre annonce',
                'download_link'=>false,
                'imagine_pattern'=>'miniatures',
                // 'attr' => [
                //     'class' => 'form-control form-control-lg form-control-a mb-3 '
                // ],
                // 'label_attr' => [
                //     'class' => 'policeForm'
                // ],
                'constraints'=>[
                    new Image([
                        'maxSize'=>'2M',
                        'maxSizeMessage'=> 'Votre image dépasse les 2Mo',
                        'mimeTypes'=>['image/png', 'image/gif', 'image/jpeg'],
                        'mimeTypesMessage'=>'Votre image doit être de type PNG, GIF ou JPEG'
                    ])
                ]
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn darkGreyBackground text-center'
                ],
                
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announcements::class,
        ]);
    }
}

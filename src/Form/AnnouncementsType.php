<?php

namespace App\Form;

use App\Entity\Announcements;
use App\Entity\Categories;
use App\Entity\Professions;
use App\Repository\ProfessionsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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
            ]) 

            //plus tard
            ->add('key_words')
            ->add('postal_code')
            ->add('gps_location')


            ->add('price_range', RangeType::class, [])



            ->add('category', EntityType::class, [
                'label'=>'Quel est le type de l\'annonce ?',
                'class'=> Categories::class,
                'choice_label'=> 'name'
            ])

            ->add('profession', EntityType::class, [
                'label'=>'Quel est le type de l\'annonce ?',
                'class'=> Professions::class,
                // 'query_builder' => function (ProfessionsRepository $er) {
                //     return $er->createQueryBuilder('p')
                //         ->
                //         ->orderBy('p.name', 'ASC');
                // },
                'choice_label'=> 'name'
            ])

            ->add('city', TextType::class, [
                // 'required' => true,
                'label'=>'Ville d\'intervention',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])


            ->add('urgency', ChoiceType::class, [
				'label' => 'Urgence de l\'annonce',
				'expanded' => true,
				'multiple' => false,
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
                'constraints'=>[
                    new Image([
                        'maxSize'=>'2M',
                        'maxSizeMessage'=> 'Votre image dépasse les 2Mo',
                        'mimeTypes'=>['image/png', 'image/gif', 'image/jpeg'],
                        'mimeTypesMessage'=>'Votre image doit être de type PNG, GIF ou JPEG'
                    ])
                ]
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

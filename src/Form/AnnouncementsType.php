<?php

namespace App\Form;

use App\Entity\Announcements;
use Symfony\Component\Form\AbstractType;
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
                'required'=>true,
                'label'=>'Vous pouvez optionnellement ajouter une note pour votre annonce',
            ]) 

            //plus tard
            ->add('ref')
            ->add('key_words')



            ->add('city', TextType::class, [
                'required' => true,
                'label'=>'Ville d\'intervention',
                'attr'=>[
                    'class'=>'form-control'
                ]
            ])
            
            ->add('postal_code')




            ->add('gps_location')
            ->add('created_at')
            ->add('price_range')
            ->add('urgency')
            ->add('slug')
            ->add('category')
            ->add('user')
            ->add('profession')
            ->add('imageFile', VichImageType::class,[
                'required' => true,
                'label'=>'Image d\'illustration de l\'article de vente',
                'download_link'=>false,
                'imagine_pattern'=>'miniatures',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez choisir une image de présentation.',
                        'groups'=> ['new']
                    ]),
                    new Image([
                        'maxSize'=>'2M',
                        'maxSizeMessage'=> 'Votre image dépasse les 2Mo',
                        'mimeTypes'=>['image/png', 'image/gif', 'image/jpeg'],
                        'mimeTypesMessage'=>'Votre image doit être de type PNG, GIF ou JPEG',
                        'groups'=> ['new', 'update']
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

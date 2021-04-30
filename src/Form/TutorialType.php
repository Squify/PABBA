<?php

namespace App\Form;

use App\Entity\ToolType;
use App\Entity\Tutorial;
use App\Entity\TutorialType as EntityTutorialType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TutorialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Saisir le titre ici...',
                ],
                'label' => 'Titre du tutoriel',
            ])
            ->add('description', CKEditorType::class, [
                "label" => "Description",
                'required' => true
            ])
            ->add('supplies', CKEditorType::class, [
                "label" => "Fournitures",
            ])
            ->add('type', EntityType::class, [
                'class' => EntityTutorialType::class,
                'choice_label' => 'label',
                "label" => "Type de tutoriel",
            ])
            ->add('tools', EntityType::class, [
                'class' => ToolType::class,
                'choice_label' => 'label',
                'multiple' => true,
                "label" => "Outils",
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'asset_helper' => true,
                'download_uri' => false,
                'download_label' => false,
                'label' => "Image d'en-tête du tutoriel",
                'attr' => [
                    'placeholder' => 'Ajouter une image',
                    'maxSize' => '2M',
                    'accept' => "image/*"
                ]
            ])
            ->add('videoFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => false,
                'asset_helper' => true,
                'download_uri' => false,
                'download_label' => false,
                'label' => "Vidéo",
                'attr' => [
                    'id' => 'videoFile',
                    'class' => 'videoFile',
                    'placeholder' => 'Ajouter une vidéo (50Mo)',
                    'maxSize' => '50M',
                    'accept' => "video/*"
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tutorial::class,
        ]);
    }
}

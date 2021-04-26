<?php

namespace App\Form;

use App\Entity\ToolType;
use App\Entity\Tutorial;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

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
//                'config' => [
//                    'placeholder' => 'Rédiger le tutoriel ici...',
//                ],
                "label" => "Description",
            ])
            ->add('supplies', CKEditorType::class, [
                "label" => "Fournitures",
                'attr' => [
                    'height' => '1500px',
                ],
            ])
            ->add('type', EntityType::class, [
                'class' => \App\Entity\TutorialType::class,
                'choice_label' => 'label',
                "label" => "Type de tutoriel",
            ])
            ->add('tools', EntityType::class, [
                'class' => ToolType::class,
                'choice_label' => 'label',
                'expanded' => false,
                'multiple' => true,
                "label" => "Outils",
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'asset_helper' => true,
                'label' => "Image d'en-tête du tutoriel",
                'attr' => [
                    'placeholder' => 'Ajouter une image',
                ],
            ])
            ->add('videoFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'asset_helper' => true,
                'label' => "Vidéo",
                'attr' => [
                    'placeholder' => 'Ajouter une vidéo (200Mo)',
                ],
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

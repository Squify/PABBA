<?php

namespace App\Form;

use App\Entity\Render;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RenderBorrowerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', TextType::class, [
                "label" => "Photo",
                "help" => "Une photo de l'outil que vous rendez"
            ])
            ->add('comment', TextareaType::class, [
                "label" => "Commentaire (facultatif)",
                "help" => "Si vous souhaitez commenter votre utilisation de l'outil ou faire une remarque particulière",
                "required" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Render::class
        ]);
    }
}

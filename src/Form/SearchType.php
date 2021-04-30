<?php

namespace App\Form;

use App\Entity\TutorialType;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre",
                "required" => false
            ])
            ->add('videoName', CheckboxType::class, [
                "label" => "Avec vidéo ?",
                "required" => false
            ])
            ->add('type', EntityType::class, [
                "label" => "Catégorie",
                "class" => TutorialType::class,
                "choice_label" => "label"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('latitude', TextType::class, [
                "label" => "Latidude"
            ])
            ->add('longitude', TextType::class, [
                "label" => "Longitude"
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'label'
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'Commentaire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}

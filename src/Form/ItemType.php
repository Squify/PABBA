<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\State;
use App\Entity\ToolType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Disponible' => 0,
                    'Indisponible' => 1,
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'asset_helper' => true,
                'download_uri' => false,
                'download_label' => false,
                'label' => "Photo de l'outil",
                'attr' => [
                    'placeholder' => 'Ajouter une image',
                    'maxSize' => '2M',
                    'accept' => "image/*"
                ]
            ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'label'
            ])
            ->add('category', EntityType::class, [
                'class' => ToolType::class,
                'choice_label' => 'label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}

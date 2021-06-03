<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\ToolType;
use App\Entity\User;
use App\Entity\EventType as EType;
use App\Form\Admin\VichImageField;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ItemSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state', EntityType::class, [
                'label' => 'État',
                'class' => State::class,
                'choice_label' => 'label',
                'required' => false,
                'placeholder' => 'Tous les états',
                'label_attr' => [
                    'class' => 'checkbox-label'
                ]
            ])
            ->add('toolType', EntityType::class, [
                'label' => 'Catégorie',
                'required' => false,
                'class' => ToolType::class,
                'choice_label' => 'label',
                'placeholder' => 'Tous les types',
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => "Nom de l'outil",
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}

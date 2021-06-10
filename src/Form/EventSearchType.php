<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Place;
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


class EventSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventAt', TextType::class, [
                'label' => "Date",
                'required' => false,

                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('eventType', EntityType::class, [
                'label' => 'Type d\'évènement',
                'class' => EType::class,
                'choice_label' => 'label',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'checkbox-label'
                ]
            ])
            ->add('place', EntityType::class, [
                'label' => 'Emplacement',
                'attr' => [
                    'class' => 'select2'
                ],
                'required' => false,
                'class' => Place::class,
                'choice_label' => 'address',
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}

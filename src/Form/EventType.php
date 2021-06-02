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


class EventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => "Renseigner le titre de l'évènement"
                ],
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => "Renseigner la description de l'évènement",
                    'rows' => 8
                ],
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('eventAt', TextType::class, [
                'label' => "Date",
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('place', EntityType::class, [
                'label' => 'Emplacement',
                'class' => Place::class,
                'choice_label' => 'address',
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('organisers', EntityType::class, [
                'label' => 'Organisateurs',
                'class' => User::class,
                'choice_label' => 'firstname',
                'multiple' => true,
                'attr' => [
                    'class' => 'select2'
                ],
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('eventType', EntityType::class, [
                'label' => 'Type d\'évènement',
                'class' => EType::class,
                'choice_label' => 'label',
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'asset_helper' => true,
                'download_uri' => false,
                'download_label' => false,
                'label' => "Photo de l'évènement",
                'help' => "Une photo de l'évènement",
                'attr' => [
                    'placeholder' => 'Ajouter une image',
                    'maxSize' => '2M',
                    'accept' => "image/*"
                ],
                'label_attr' => [
                    'class' => 'create_event_label'
                ]
        ])
        ;

        $builder->get('eventAt')
            ->addModelTransformer(new CallbackTransformer(
                function($dateToString){
                    if ($dateToString == null) {
                        return date("d/m/Y H:i");
                    }
                    return date("d/m/Y H:i", $dateToString->getTimestamp());
                },
                function($stringToDate){
                    return \DateTime::createFromFormat("d/m/Y H:i", $stringToDate);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if (!$data->getId()) {
                    return ['Event', 'event_create'];
                }

                return ['Event'];
            },
        ]);
    }
}

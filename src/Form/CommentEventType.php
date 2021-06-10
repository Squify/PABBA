<?php

namespace App\Form;

use App\Entity\CommentEvent;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du commentaire',
                'required' => true
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description',
                'required' => true
            ])
            ->add('event', EntityType::class, [
                'required' => false,
                'class' => Event::class,
                'attr' => [
                    'class' => 'd-none'
                ],
                'label_attr' => [
                    'class' => 'd-none'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentEvent::class,
        ]);
    }
}

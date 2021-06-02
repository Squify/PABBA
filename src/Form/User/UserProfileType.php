<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use function Symfony\Component\String\b;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['data']->getId()){
            $builder  ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true
            ]);
        }
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('pictureFile', VichImageType::class, [
                'label' => 'Photo de profil',
                'required' => true,
                'allow_delete' => false,
                'asset_helper' => true,
                'download_uri' => false,
                'download_label' => false,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => true
            ])
            ->add('birthdate', DateType::class, [
                'label' => 'Anniversaire',
                'required' => true,
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

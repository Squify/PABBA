<?php

namespace App\Form;

use App\Entity\Rent;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DataTransformerChain;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ItemRentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rentAt', TextType::class, [
                'label' => 'Emprunter le'
            ])
            ->add('returnAt', TextType::class, [
                'label' => 'Retour le'
            ])
        ;

        $builder->get("rentAt")->addModelTransformer(new CallbackTransformer(
            function($dateToString){
                if ($dateToString == null) {
                    return null;
                }
                return date("d/m/Y H:i", $dateToString);
            },
            function($stringToDate){
                return DateTime::createFromFormat("d/m/Y H:i", $stringToDate);
            }
        ));

        $builder->get("returnAt")->addModelTransformer(new CallbackTransformer(
            function($dateToString){
                if ($dateToString == null) {
                    return null;
                }
                return date("d/m/Y H:i", $dateToString);
            },
            function($stringToDate){
                return DateTime::createFromFormat("d/m/Y H:i", $stringToDate);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
        ]);
    }
}

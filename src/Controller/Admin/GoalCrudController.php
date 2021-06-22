<?php


namespace App\Controller\Admin;

use App\Entity\Goal;
use App\Form\Admin\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GoalCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Goal::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Objectifs')
            ->setEntityLabelInSingular('Objectif')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelle', 'Libellé'),
            IntegerField::new('objective', 'Objectif'),
            TextareaField::new('description', 'Description'),
            AssociationField::new('reward', "Récompense"),
            ChoiceField::new('type', 'Type')->setChoices([
                 'Événements organisés' => Goal::TYPE_EVENT_ORGANIZED,
                 'Événements suivis' => Goal::TYPE_EVENT_PARTICIPATED,
                 'Lieux proposés' => Goal::TYPE_PLACES,
                 'Objets loués' => Goal::TYPE_RENTS,
                 'Tutoriels ajoutés' => Goal::TYPE_TUTORIAL
            ]),
            BooleanField::new('active', 'Actif')->onlyOnIndex()

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}

<?php


namespace App\Controller\Admin;

use App\Entity\Goal;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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
            AssociationField::new('toCount', "À compter"),
            TextareaField::new('description', 'Description'),
            ImageField::new("image", "Image")->setBasePath("/images/goals")->hideOnForm(),
            AssociationField::new('reward', "Récompense"),
            BooleanField::new('active', 'Actif')->onlyOnIndex()

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}

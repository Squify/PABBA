<?php


namespace App\Controller\Admin;

use App\Entity\Goal;
use App\Entity\ToCount;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ToCountCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return ToCount::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('À compter')->setEntityLabelInSingular('À compter');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('libelle', 'Libellé'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}

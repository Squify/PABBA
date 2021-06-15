<?php


namespace App\Controller\Admin;


use App\Entity\Rank;
use App\Entity\Reward;
use App\Form\Admin\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RankCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Rank::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id", "#")->hideOnForm(),
            TextField::new('name', 'Nom'),
            IntegerField::new('start', 'Début'),
            IntegerField::new('end', 'Fin'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}
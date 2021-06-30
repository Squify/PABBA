<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Entity\Partner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PartnerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partner::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Partenaires')
            ->setEntityLabelInSingular('Partenaire')
            ->overrideTemplate('crud/field/boolean' ,'admin/partner__isActive__index.html.twig')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextField::new('link', 'Lien'),
            BooleanField::new('isActive', 'Actif')->onlyOnIndex()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action ->displayIf(static function (Partner $partner) {
                    return $partner->getRewards()->count() == 0;
                });
            });
    }
}

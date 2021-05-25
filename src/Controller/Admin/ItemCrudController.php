<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Form\Admin\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            VichImageField::new('imageFile', 'Image')->onlyOnForms(),
            AssociationField::new('state', 'État'),
            BooleanField::new('status', 'Disponible'),
            AssociationField::new('owner','Propriétaire')->onlyOnForms(),
            AssociationField::new('category','Catégorie')->onlyOnForms()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action ->displayIf(static function (Item $item) {
                    return $item->getRents(true)->count() == 0;
                });
            });
    }
}

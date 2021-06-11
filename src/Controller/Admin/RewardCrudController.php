<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Entity\Reward;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class RewardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reward::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Récompenses')
            ->setEntityLabelInSingular('Récompense')
            ->overrideTemplate('crud/field/boolean' ,'admin/reward__isActive__index.html.twig')

            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('description','Description'),
            AssociationField::new('partner','Partenaire')->setQueryBuilder(function ($q) {
                $q->andWhere('entity.isActive = 1');
            }),
            UrlField::new('link', 'Lien')->onlyOnForms(),
            BooleanField::new('isActive', 'Active')->onlyOnIndex()
        ];
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action ->displayIf(static function (Reward $reward) {
                    return $reward->getGoals()->count() == 0;
                });
            });
    }
}

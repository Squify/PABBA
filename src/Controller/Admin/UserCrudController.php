<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('lastLogin', 'Dernière connexion')->onlyOnIndex(),
            TextField::new('email', 'Email'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            BooleanField::new('enable','Actif'),
            TextField::new('phone', 'Téléphone')->onlyOnForms(),
            DateField::new('birthdate', 'Anniversaire')->onlyOnForms(),
        ];
    }
}

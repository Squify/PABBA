<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\Admin\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('&Eacute;vénements')->setEntityLabelInSingular('événement');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new("imageName", "Image")->setBasePath("/images/events")->hideOnForm(),
            TextField::new('title', 'Titre'),
            AssociationField::new('place', "Emplacement")->formatValue( function($value){
                return substr($value, 0, 30)."...";
            }),
            AssociationField::new('eventType', "Type"),
            DateTimeField::new("eventAt", "A lieu le"),
        ];
    }


    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         TextField::new('name', 'Nom'),
    //         VichImageField::new('imageFile', 'Image')->onlyOnForms(),
    //         AssociationField::new('state', 'État'),
    //         BooleanField::new('status', 'Disponible'),
    //         AssociationField::new('owner','Propriétaire')->onlyOnForms(),
    //         AssociationField::new('category','Catégorie')->onlyOnForms()
    //     ];
    // }

    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions
    //         // ...
    //         ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
    //             return $action ->displayIf(static function (Item $item) {
    //                 return $item->getRents(true)->count() == 0;
    //             });
    //         });
    // }
}

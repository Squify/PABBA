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
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

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
            IdField::new("id", "#")->hideOnForm(),
            TextField::new('title', 'Titre'),
            AssociationField::new('place', "Emplacement")->formatValue( function($value){
                return substr($value, 0, 30)."...";
            }),
            DateTimeField::new("eventAt", "A lieu le"),
            AssociationField::new('eventType', "Type"),
            ImageField::new("imageName", "Image")->setBasePath("/images/events")->hideOnForm(),
            // AssociationField::new("organisers", "Organisateurs")->onlyOnForms(),
            // AssociationField::new("participants", "Participants")->onlyOnForms(),
        ];
    }

}

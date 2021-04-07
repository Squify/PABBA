<?php

namespace App\Controller\Admin;

use App\Entity\Place;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\ActionDto;

class PlaceCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Place::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->where('entity.isValid = 0');

        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setPageTitle("index", "Lieux à valider");
    }

    public function configureActions(Actions $actions): Actions
    {
        $valider = Action::new('validate', 'Valider')
            ->linkToCrudAction('validatePlace')
            ->setCssClass("btn btn-success");

        $refuser = Action::new('refuse', 'Refuser')
            ->linkToCrudAction('refusePlace')
            ->setCssClass("btn btn-danger");

        

        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, $refuser)
            ->add(Crud::PAGE_INDEX, $valider);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Proposé par'),
            AssociationField::new('type', 'Type'),
            TextField::new('address', 'Adresse'),
            TextField::new('iat', 'IAT'),
            TextField::new('ion', 'ION'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('type');
    }

    public function validatePlace(AdminContext $context)
    {
        /**
         * @var Place
         */
        $place = $context->getEntity()->getInstance();

        $place->setIsValid(true);

        $this->getDoctrine()->getManager()->flush();

        $link = "http://localhost:33/admin";
        foreach ($context->getMainMenu()->getItems() as $menuItem) {
            if ($menuItem->getLabel() == "Lieux") {
                $link = $menuItem->getLinkUrl();
                break;
            }
        }

        return $this->redirect($link);
    }

    public function refusePlace(AdminContext $context)
    {
        /**
         * @var Place
         */
        $place = $context->getEntity()->getInstance();

        $this->getDoctrine()->getManager()->remove($place);
        $this->getDoctrine()->getManager()->flush();

        $link = "http://localhost:33/admin";
        foreach ($context->getMainMenu()->getItems() as $menuItem) {
            if ($menuItem->getLabel() == "Lieux") {
                $link = $menuItem->getLinkUrl();
                break;
            }
        }

        return $this->redirect($link);
    }
}

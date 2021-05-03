<?php

namespace App\Controller\Admin;

use App\Entity\Tutorial;
use App\Services\MailerService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class TutorialCrudController extends AbstractCrudController
{
    private MailerService $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public static function getEntityFqcn(): string
    {
        return Tutorial::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $alertAction = Action::new('Alerte', 'Alerter')
            ->setIcon('fas fa-exclamation-circle')
            ->linkToCrudAction('alertAction');

        return $actions
            ->add(Crud::PAGE_INDEX, $alertAction);

    }

    public function alertAction(AdminContext $context)
    {
        $id     = $context->getRequest()->query->get('entityId');
        $tutorial = $this->getDoctrine()->getRepository(Tutorial::class)->find($id);

        $this->mailerService->sendAlertTutorial($tutorial);

        $this->addFlash('success', "L'alerte a bien été envoyée à " . $tutorial->getUser()->getEmail());

        return $this->redirect($this->get(CrudUrlGenerator::class)->build()->setAction(Action::INDEX)->generateUrl());
    }

    // Elle permet de défini les champs qui sont utilisés pour l'affichage et les formulaires
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'User')->onlyOnIndex(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('description', 'Description'),
            BooleanField::new('disable', 'Desactiver'),
            CollectionField::new('supplies', 'Fournitures'),
        ];
    }

}

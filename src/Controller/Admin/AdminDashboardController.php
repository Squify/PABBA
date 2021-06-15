<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Goal;
use App\Entity\Item;
use App\Entity\Moderation;
use App\Entity\Partner;
use App\Entity\Place;
use App\Entity\Rank;
use App\Entity\Reward;
use App\Entity\Tutorial;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('PABBA');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Lieux', 'fas fa-map-marker-alt', Place::class);
        yield MenuItem::linkToCrud('Événements', 'fas fa-calendar-alt', Event::class);
        yield MenuItem::linkToCrud('Tutoriels', 'fab fa-youtube', Tutorial::class);
        yield MenuItem::linkToCrud('Outils', 'fas fa-tools', Item::class);
        yield MenuItem::linkToCrud('Modérations', 'fas fa-comment', Moderation::class);
        yield MenuItem::linkToCrud('Partenaires', 'fas fa-handshake', Partner::class);
        yield MenuItem::linkToCrud('Récompenses', 'fas fa-medal', Reward::class);
        yield MenuItem::linkToCrud('Grades', 'fas fa-graduation-cap', Rank::class);
        yield MenuItem::linkToCrud('Objectifs', 'fas fa-bullseye', Goal::class);
    }
}

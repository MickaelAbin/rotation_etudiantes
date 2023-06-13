<?php

namespace App\Controller\Admin;

use App\Entity\AcademicLevel;
use App\Entity\Admin;
use App\Entity\ClinicalRotationCategory;
use App\Entity\Enrolment;
use App\Entity\Student;
use App\Entity\UniversityCalendar;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route(path = "/admin", name = "admin")
     */
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(AdminCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gardes étudiantes')
            ->setFaviconPath('assets/moodle_favicon.ico')
            ->generateRelativeUrls()
            ;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin.css');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Service Formation');
        yield MenuItem::linkToCrud('Administrateurs', 'far fa-user', Admin::class);

        yield MenuItem::section('Promotions');
        yield MenuItem::linkToCrud('Étudiants', 'fas fa-user', Student::class);
        yield MenuItem::linkToCrud('Calendriers universitaires', 'fas fa-calendar', UniversityCalendar::class);
        yield MenuItem::linkToCrud('Catégories de garde', 'fas fa-notes-medical', ClinicalRotationCategory::class);

        yield MenuItem::section('Plannings de gardes');
        yield MenuItem::linkToRoute('Attribution des gardes', 'fas fa-list', 'enrolment_allocation', ['id' => 2]);
        yield MenuItem::linkToCrud('Créneau d\'urgence', 'fas fa-first-aid', Enrolment::class)->setAction(Action::NEW);

        yield MenuItem::section('Super Admin')->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Niveaux académiques', 'fas fa-graduation-cap', AcademicLevel::class)->setPermission('ROLE_SUPER_ADMIN');

        yield MenuItem::section();
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-home', 'calendar', ['id' => 2]);
    }
}

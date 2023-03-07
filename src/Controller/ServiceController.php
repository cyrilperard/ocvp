<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/service')]
class ServiceController extends AbstractController
{
    #[Route('/all', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'pageTitle' => 'service',
            'controller_name' => 'ServiceController',
            'first_title' => "Nos services",
            'second_title' => "Pour votre projets",
        ]);
    }
    #[Route('/website', name: 'app_service_website')]
    public function website(): Response
    {
        return $this->render('service/website.html.twig', [
            'pageTitle' => 'service',
            'controller_name' => 'ServiceController',
            'first_title' => "Site Web",
            'second_title' => "Pour votre projets",
        ]);
    }
    #[Route('/application-web', name: 'app_service_application_web')]
    public function applicationWeb(): Response
    {
        return $this->render('service/application-web.html.twig', [
            'pageTitle' => 'service',
            'controller_name' => 'ServiceController',
            'first_title' => "Application Web",
            'second_title' => "Pour votre projets",
        ]);
    }
    #[Route('/hosting', name: 'app_service_hosting')]
    public function hosting(): Response
    {
        return $this->render('service/hosting.html.twig', [
            'pageTitle' => 'service',
            'controller_name' => 'ServiceController',
            'first_title' => "Hébergement",
            'second_title' => "Pour votre projets",
        ]);
    }
    #[Route('/extranet', name: 'app_service_extranet')]
    public function extranet(): Response
    {
        return $this->render('service/extranet.html.twig', [
            'pageTitle' => 'service',
            'controller_name' => 'ServiceController',
            'first_title' => "Extranet",
            'second_title' => "Pour votre projets",
        ]);
    }
    #[Route('/intranet', name: 'app_service_intranet')]
    public function intranet(): Response
    {
        return $this->render('service/intranet.html.twig', [
            'pageTitle' => 'service',
            'controller_name' => 'ServiceController',
            'first_title' => "Intranet",
            'second_title' => "Pour votre projets",
        ]);
    }
}

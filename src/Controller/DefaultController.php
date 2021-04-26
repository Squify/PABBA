<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Mobile_Detect;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $detect = new Mobile_Detect;
        return $this->render('homepage.html.twig', [
            'controller_name' => 'DefaultController',
            "isTablet" => $detect->isTablet(),
            "isMobile" => $detect->isMobile(),
        ]);
    }
}

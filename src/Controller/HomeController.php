<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home_index")
     */
    public function index(PlaceRepository $placeRepository)
    {

        return $this->render("home/index.html.twig", [
            "places" => $placeRepository->findAll()
        ]);
    }

}
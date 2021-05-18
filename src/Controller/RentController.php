<?php

namespace App\Controller;

use App\Repository\RentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/emprunt")
 */
class RentController extends AbstractController
{

    /**
     * @Route("", name="rent_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(RentRepository $rentRepository): Response
    {
        $user = $this->getUser();
        return $this->render('rent/index.html.twig', [
            // 'rented' => $user ? $rentRepository->findAllByRenterIdOrderByDate($user->getId()) : [],
            // 'loaned' => $user ? $rentRepository->findAllByOwnerIdOrderByDate($user->getId()) : [],
            'rented' => $rentRepository->findAllByRenterIdOrderByDate($user->getId()),
            'loaned' => $rentRepository->findAllByOwnerIdOrderByDate($user->getId()),
        ]);
    }


}

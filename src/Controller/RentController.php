<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\RentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/emprunt")
 */
class RentController extends AbstractController
{

    /**
     * @Route("", name="rent_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(RentRepository $rentRepository, ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        return $this->render('rent/index.html.twig', [
            'rented' => $user ? $rentRepository->findAllByRenterIdOrderByDate($user->getId()) : [],
            'loaned' => $user ? $rentRepository->findAllByOwnerIdOrderByDate($user->getId()) : [],
            'items' => $user ? $itemRepository->findAllByOwnerId($user->getId()) : [],
        ]);
    }


}

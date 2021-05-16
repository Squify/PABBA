<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rent;
use App\Form\ItemType;
use App\Form\ItemRentType;
use App\Repository\ItemRepository;
use App\Repository\RentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/outil")
 */
class ItemController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("", name="rent_index", methods={"GET"})
     */
    public function index(RentRepository $rentRepository): Response
    {
        $user = $this->getUser();
        return $this->render('rent/index.html.twig', [
            'rented' => $user ? $rentRepository->findAllByRenterIdOrderByDate($user->getId()) : [],
            'loaned' => $user ? $rentRepository->findAllByOwnerIdOrderByDate($user->getId()) : [],
        ]);
    }

    /**
     * @Route("/liste", name="item_index", methods={"GET"})
     */
    public function itemIndex(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/creer", name="item_create", methods={"GET","POST"})
     */
    public function create(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setOwner($this->getUser());
            // $entityManager = $this->getDoctrine()->getManager();
            $this->manager->persist($item);
            $this->manager->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"})
     */
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * @Route("/editer/{id}", name="item_update", methods={"GET","POST"})
     */
    public function update(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"POST"})
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            // $entityManager = $this->getDoctrine()->getManager();
            $this->manager->remove($item);
            $this->manager->flush();
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("/emprunter/{id}", name="item_rent", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function rent(Item $item, Request $request)
    {
        $rent = new Rent();
        $form = $this->createForm(ItemRentType::class, $rent);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $rent
                ->setItem($item)
                ->setOwner($item->getOwner())
                ->setRenter($this->getUser());

            $this->manager->persist($rent);
            $this->manager->flush();

            $this->addFlash("success", "L'emprunt est bien validé");
            return $this->redirectToRoute("item_index");
        }

        return $this->render("item/rent.html.twig", [
            "form" => $form->createView(),
            "rent" => $rent,
            "item" => $item
        ]);
    }
}

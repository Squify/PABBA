<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rent;
use App\Form\ItemType;
use App\Form\ItemBorrowType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="item_index", methods={"GET"})
     */
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Item $item): Response
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
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            // $entityManager = $this->getDoctrine()->getManager();
            $this->manager->remove($item);
            $this->manager->flush();
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("/{id}/borrow", name="item_borrow", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function borrow(Item $item, Request $request)
    {

        $rent = new Rent();

        $form = $this->createForm(ItemBorrowType::class, $rent);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
            $rent
                ->setItem($item)
                ->setOwner($item->getOwner())
                ->setRenter($this->getUser());

            // dd($rent, $request);

            $this->manager->persist($rent);
            $this->manager->flush();

            $this->addFlash("success", "L'emprunt est bien validé");
            return $this->redirectToRoute("item_index");
        }

        return $this->render("item/borrow.html.twig", [
            "form" => $form->createView(),
            "rent" => $rent,
            "item" => $item
        ]);

    }
}

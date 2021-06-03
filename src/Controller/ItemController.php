<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Rent;
use App\Form\EventSearchType;
use App\Form\ItemSearchType;
use App\Form\ItemType;
use App\Form\ItemBorrowType;
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
    /**
     * @var ItemRepository
     */
    private ItemRepository $itemRepository;

    /**
     * ItemController constructor.
     * @param EntityManagerInterface $manager
     * @param ItemRepository $itemRepository
     */
    public function __construct(EntityManagerInterface $manager, ItemRepository $itemRepository)
    {
        $this->manager = $manager;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @Route("", name="item_index", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('item/index.html.twig');
    }

    /**
     * @Route("/search", name="item_search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $form = $this->createForm(ItemSearchType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $items = $this->itemRepository->findByFilters($form->getData());
        }else{
            $items = $this->itemRepository->findByStatus(0);
        }

        return $this->json([
            'form' => $this->render("components/_search_form.html.twig", [
                'form' => $form->createView(),
            ])->getContent(),
            'content' => $this->render("item/components/_rows.html.twig", [
                'items' => $items
            ])->getContent()
        ]);

    }

    /**
     * @Route("/creer", name="item_create", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
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
//            $item->setPicture($item->getImageFile());
//            dd($item->getImageFile());
            $this->manager->flush();

            return $this->redirectToRoute('rent_index');
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="item_delete")
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            // $entityManager = $this->getDoctrine()->getManager();
            $this->manager->remove($item);
            $this->manager->flush();
        }

        return $this->redirectToRoute('rent_index');
    }

    /**
     * @Route("/emprunter/{id}", name="item_borrow", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function borrow(Item $item, Request $request)
    {
        $rent = new Rent();

        if ($item->getOwner()->getId() == $this->getUser()->getId()) {
            $this->addFlash("error", "Vous ne pouvez emprunter un de vos propres outils");
            return $this->redirectToRoute("item_index");
        }


        $form = $this->createForm(ItemBorrowType::class, $rent);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $rent
                ->setItem($item)
                ->setOwner($item->getOwner())
                ->setRenter($this->getUser());

            $rent->getItem()->setStatus(1);

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

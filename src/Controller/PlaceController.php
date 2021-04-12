<?php


namespace App\Controller;


use App\Entity\Place;
use App\Entity\User;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PlaceController extends AbstractController
{


    private PlaceRepository $PlaceRepository;

    public function __construct()
    {

    }

    /**
     * @Route("/lieu/creer", name="place_create")
     */
    public function create(Request $request, EntityManagerInterface $manager, Security $security)
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $place->setIsValid(false)
                ->setUser($this->getUser());
            $manager->persist($place);
            $manager->flush();

            return $this->redirectToRoute("place_create");
        }

        return $this->render("places/create.html.twig", [
            "form" => $form->createView(),
            "place" => $place
        ]);
    }

    /**
     * @Route("/lieu/editer/{id}", name="place_update")
     * @param EntityManagerInterface $manager
     * @param Int $id
     * @param Request $request
     */
    public function update(EntityManagerInterface $manager,int $id, Request $request, PlaceRepository $placeRepository)
    {
        $place = $placeRepository->find($id);
        if (!$place) {
            $this->addFlash("danger", "STOPPPPP");
            return $this->redirectToRoute("place_create");
        }

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $manager->flush();

            return $this->redirectToRoute("place_create");
        }

        return $this->render("places/update.html.twig", [
            "form" => $form->createView(),
            "place" => $place
        ]);
    }


    /**
     * @Route("/lieu/afficher/{id}", name="place_details")
     * @param int $id
     * @param PlaceRepository $placeRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function details (int $id, PlaceRepository $placeRepository)
    {
        $place = $placeRepository->find($id);

        if (!$place) {
            $this->addFlash("danger", "lieu non trouvé");
            return $this->redirectToRoute("place_create");
        }
        return $this->render("places/details.html.twig", [
            "place" => $place
        ]);
    }


}
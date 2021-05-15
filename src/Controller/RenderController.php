<?php

namespace App\Controller;

use App\Entity\Render;
use App\Form\RenderBorrowerType;
use App\Repository\RenderRepository;
use App\Repository\RentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/rendu")
 */
class RenderController extends AbstractController
{

    private $renderRepository;
    private $rentRepository;
    private $manager;

    public function __construct(RenderRepository $renderRepository, RentRepository $rentRepository, EntityManagerInterface $manager)
    {

        $this->renderRepository = $renderRepository;
        $this->rentRepository = $rentRepository;
        $this->manager = $manager;
        
    }

    /**
     * @Route("/{rentId}", name="render_create")
     */
    public function create($rentId, Request $request)
    {

        // $render = new Render();

        $form = $this->createForm(RenderBorrowerType::class, $render = new Render());

        $form->handleRequest($request);
        
        $render->setRent($this->rentRepository->find($rentId));

        if ($form->isSubmitted()) {
            
            $render
                ->setDoneAt(new DateTime())
                ->setIsValid(0)
            ;

            // dd($render);

            $this->manager->persist($render);
            $this->manager->flush();

            $this->addFlash("success", "Votre confirmation de rendu à bien été envoyée au propriétaire de l'outil pour confirmation");

            // Prévenir le propriétaire qu'il doit confirmer le rendu
            #####




            #####

            #### ---- A remplacer par la route vers la page "Mes emprunts"
            return $this->redirectToRoute("index");

        }

        return $this->render("render/create.html.twig", [
            "form" => $form->createView(),
            "render" => $render
        ]);

    }

}
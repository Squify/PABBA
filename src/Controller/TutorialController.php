<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Form\TutorialType;
use App\Repository\TutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TutorialController extends AbstractController
{

    private $tutorialRepository;

    public function __construct(TutorialRepository $tutorialRepository)
    {
        $this->tutorialRepository = $tutorialRepository;
    }

    /**
     * @Route("/tutoriel", name="tutorial_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render("tutorials/index.html.twig", [
        ]);
    }

    /**
     * @Route("/tutoriel/creer", name="tutorial_create")
     */
    public function create(Request $request, EntityManagerInterface $manager, Security $security)
    {
        $tutorial = new Tutorial();
        $form = $this->createForm(TutorialType::class, $tutorial);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $tutorial
                ->setDisable(false)
                ->setUser($this->getUser());
            $manager->persist($tutorial);
            $manager->flush();

            return $this->redirectToRoute("tutorial_index");
        }

        return $this->render("tutorials/create.html.twig", [
            "form" => $form->createView(),
            "tutorial" => $tutorial,
        ]);
    }

    /**
     * @Route("/tutoriel/editer/{id}", name="tutorial_update")
     * @param EntityManagerInterface $manager
     * @param Int $id
     * @param Request $request
     */
    public function update(EntityManagerInterface $manager, int $id, Request $request, TutorialRepository $tutorialRepository)
    {
        $tutorial = $tutorialRepository->find($id);
        if (!$tutorial) {
            $this->addFlash("danger", "STOPPPPP");
            return $this->redirectToRoute("tutorial_create");
        }

        $form = $this->createForm(TutorialType::class, $tutorial);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $manager->flush();

            return $this->redirectToRoute("tutorial_index");
        }

        return $this->render("tutorials/update.html.twig", [
            "form" => $form->createView(),
            "tutorial" => $tutorial,
        ]);
    }
}

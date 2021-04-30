<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Form\TutorialType;
use App\Repository\TutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TutorialController extends AbstractController
{

    private $tutorialRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * TutorialController constructor.
     * @param TutorialRepository $tutorialRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(TutorialRepository $tutorialRepository, EntityManagerInterface $em)
    {
        $this->tutorialRepository = $tutorialRepository;
        $this->em = $em;
    }

    /**
     * @Route("/tutoriel", name="tutorial_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // $tutorials = $this->tutorialRepository->findby(
        //     ['disable' => 0]
        // );

        return $this->render("tutorials/index.html.twig", [
            'tutorials' => $this->tutorialRepository->findby(
                ['disable' => 0]
            )
        ]);
    }

    /**
     * @Route("/tutoriel/creer", name="tutorial_create")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $tutorial = new Tutorial();
        $form = $this->createForm(TutorialType::class, $tutorial);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tutorial
                ->setDisable(false)
                ->setUpdatedAt(new \DateTime())
                ->setUser($this->getUser());
            $this->em->persist($tutorial);
            $this->em->flush();

            $this->addFlash("success", "Le tutoriel a bien été créé");
            return $this->redirectToRoute("user_tutorial");
        }

        return $this->render("tutorials/create.html.twig", [
            "form" => $form->createView(),
            "tutorial" => $tutorial,
        ]);
    }

    /**
     * @Route("/tutoriel/editer/{id}", name="tutorial_update")
     * @param Request $request
     * @param Tutorial $tutorial
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, Tutorial $tutorial)
    {
        $form = $this->createForm(TutorialType::class, $tutorial);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash("success", "Le tutoriel a bien été modifié");
            return $this->redirectToRoute("user_tutorial");
        }

        return $this->render("tutorials/update.html.twig", [
            "form" => $form->createView(),
            "tutorial" => $tutorial,
        ]);
    }


    /**
     * @Route("/profile/tutoriel", name="user_tutorial")
     * @param Request $request
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myTutorial(Request $request)
    {
        $tutorial =  $this->tutorialRepository->findByUser($this->getUser());
        dump($tutorial);

        return $this->render("profile/tutorial.html.twig", [
            'tutorials' => $tutorial,
        ]);
    }


    /**
     * @Route("/tutoriel/detail/{id}", name="tutorial_details")
     * @param Tutorial $tutorial
     * @return Response
     */
    public function details(Tutorial $tutorial)
    {

        return $this->render("tutorials/details.html.twig", [
            'tutorial' => $tutorial,
        ]);
    }

    /**
     * @Route("/tutoriel/supprimer/{id}", name="tutorial_delete")
     * @param EntityManagerInterface $manager
     * @param int $id
     * @param Request $request
     */
    public function delete(EntityManagerInterface $manager, int $id, Request $request   )
    {
        $tutorial = $this->tutorialRepository->find($id);
        if (!$tutorial) {
            $this->addFlash("danger", "Le tutoriel supprimé n'existe pas, veuillez en séléctionner un nouveau");
            return $this->redirectToRoute("user_tutorial");
        }

        $manager->remove($tutorial);
        $manager->flush();
        return $this->redirectToRoute("user_tutorial");
    }
}

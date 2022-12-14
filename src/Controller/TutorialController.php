<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Form\ItemSearchType;
use App\Form\TutorialSearchType;
use App\Form\TutorialType;
use App\Entity\CommentTutorial;
use App\Form\CommentTutorialType;
use App\Repository\TutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function index()
    {
        return $this->render("tutorials/index.html.twig");
    }

    /**
     * @Route("/tutorial/search", name="tutorial_search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $form = $this->createForm(TutorialSearchType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $tutorials = $this->tutorialRepository->findSearchResults($form->getData());
        }else{
            $tutorials = $this->tutorialRepository->findByDisable(0);
        }

        return $this->json([
            'form' => $this->render("components/_search_form.html.twig", [
                'form' => $form->createView(),
            ])->getContent(),
            'content' => $this->render("tutorials/components/_rows.html.twig", [
                'tutorials' => $tutorials
            ])->getContent()
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

            $this->addFlash("success", "Le tutoriel a bien ??t?? cr????");
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
            $this->addFlash("success", "Le tutoriel a bien ??t?? modifi??");
            $this->em->flush();
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
            'commentForm' => $tutorial->getCommentTutorials($this->getUser())->count() <= 0 && $this->getUser(),
        ]);
    }

    /**
     * @Route("/tutoriel/supprimer/{id}", name="tutorial_delete")
     * @param EntityManagerInterface $manager
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(EntityManagerInterface $manager, int $id, Request $request   )
    {
        $tutorial = $this->tutorialRepository->find($id);

        if (!$tutorial) {
            $this->addFlash("error", "Le tutoriel n'existe pas, veuillez en s??l??ctionner un nouveau");
            return $this->redirectToRoute("user_tutorial");
        }

        $manager->remove($tutorial);
        $manager->flush();
        $this->addFlash("success", "Le tutoriel a ??t?? supprim??");

        return $this->redirectToRoute("user_tutorial");
    }
}

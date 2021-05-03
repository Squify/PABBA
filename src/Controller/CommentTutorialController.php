<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Entity\CommentTutorial;
use App\Form\CommentTutorialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentTutorialController extends AbstractController
{
    /**
     * @Route("/comment/tutorial/create", name="comment_tutorial_create")
     */
    public function create(Tutorial $tutorial, Request $request, EntityManagerInterface $manager, Security $security)
    {
        $commentTutorial = new CommentTutorial();
        $form = $this->createForm(CommentTutorialType::class, $commentTutorial);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentTutorial
                ->setTutorial($tutorial)
                ->setAuteur($this->getUser());
            $manager->persist($commentTutorial);
            $manager->flush();

            $this->addFlash("notice", "Le commentaire à bien été créé");
            return "ok";
        }

        return $this->render('comment_tutorial/create.html.twig', [
            "form" => $form->createView()
        ]);
    }
}

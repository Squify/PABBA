<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Entity\CommentTutorial;
use App\Form\CommentTutorialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentTutorialController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * CommentTutorialController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/comment/tutorial/process", name="comment_tutorial_update")
     * @param Request $request
     * @return false|string
     */
    public function process(Request $request)
    {
        $id = $request->query->get('id', null);

        $commentTutorial = $id ? $this->em->getRepository(CommentTutorial::class)->find($id) : new CommentTutorial();

        $form = $this->createForm(CommentTutorialType::class, $commentTutorial);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentTutorial
                ->setAuteur($this->getUser());
            $this->em->persist($commentTutorial);
            $this->em->flush();

            return new JsonResponse($this->renderView('tutorials/_comments.html.twig', [
                'tutorial' => $commentTutorial->getTutorial()
            ]));
        }

        return new JsonResponse($form->getErrors(true), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/comment/tutorial/form", name="comment_tutorial_form")
     * @param Request $request
     * @return JsonResponse
     */
    public function getForm(Request $request){
        $id = $request->query->get('id', null);

        $commentTutorial = $id ? $this->em->getRepository(CommentTutorial::class)->find($id) : new CommentTutorial();

        if(!$id){
            $commentTutorial->setTutorial($this->em->getRepository(Tutorial::class)->find($request->query->get('tutorial', null)));
        }

        return new JsonResponse($this->renderView('tutorials/_commentModalForm.html.twig', [
            'form' => $this->createForm(CommentTutorialType::class, $commentTutorial)->createView()
        ]));

    }
}

<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Entity\CommentEvent;
use App\Form\CommentEventType;
use App\Entity\CommentTutorial;
use App\Entity\Event;
use App\Form\CommentTutorialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Event\ConsoleEvent;

class CommentEventController extends AbstractController
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
     * @Route("/comment/event/process", name="comment_event_update")
     * @param Request $request
     * @return false|string
     */
    public function process(Request $request)
    {
        $id = $request->query->get('id', null);

        $commentEvent = $id ? $this->em->getRepository(CommentEvent::class)->find($id) : new CommentEvent();

        $form = $this->createForm(CommentEventType::class, $commentEvent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentEvent
                ->setCreatedAt(new \DateTime('now'))
                ->setAuteur($this->getUser());
            $this->em->persist($commentEvent);
            $this->em->flush();

            return new JsonResponse($this->renderView('event/_comments.html.twig', [
                'event' => $commentEvent->getEvent()
            ]));
        }

        return new JsonResponse($form->getErrors(true), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/comment/event/delete", name="comment_event_delete")
     * @param Request $request
     * @return false|string
     */
    public function delete(Request $request)
    {
        $id = $request->query->get('id', null);

        $commentEvent = $id ? $this->em->getRepository(CommentEvent::class)->find($id) : new CommentEvent();

        $event = $commentEvent->getEvent();

        $this->em->remove($commentEvent);
        $this->em->flush();
        
        return new JsonResponse($this->renderView('event/_comments.html.twig', [
            'event' => $event
        ]));

        }

    /**
     * @Route("/comment/event/form", name="comment_event_form")
     * @param Request $request
     * @return JsonResponse
     */
    public function getForm(Request $request){
        $id = $request->query->get('id', null);
        $commentEvent = $id ? $this->em->getRepository(CommentEvent::class)->find($id) : new CommentEvent();
        
        if(!$id){
            $commentEvent->setEvent($this->em->getRepository(Event::class)->find($request->query->get('event', null)));
        }

        return new JsonResponse($this->renderView('event/_commentModalForm.html.twig', [
            'form' => $this->createForm(CommentEventType::class, $commentEvent)->createView()
        ]));

    }
}

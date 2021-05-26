<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventController
 * @package App\Controller
 * @Route("/evenement")
 */
class EventController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("", name="event_index")
     */
    public function index()
    {
        return $this->render("event/index.html.twig", []);
    }

    /**
     * @Route("/creer", name="event_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function create(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setIsPublished(false);
            $this->manager->persist($event);
            $this->manager->flush();
            $this->addFlash("success", "L'évènement a bien été créé");
            return $this->redirectToRoute("event_index");
        }

        return $this->render("event/create.html.twig", [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }


    /**
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Event $event)
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash("success", "L'évènement a bien été modifié");
            $this->manager->flush();
            return $this->redirectToRoute("event_index");
        }

        return $this->render("event/update.html.twig", [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }
}
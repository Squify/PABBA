<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    private $eventRepository;

    public function __construct(EntityManagerInterface $manager, EventRepository $eventRepository)
    {
        $this->manager = $manager;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @Route("", name="event_index")
     */
    public function index()
    {
        return $this->render("event/index.html.twig", [
            "events" => $this->eventRepository->findAll()
        ]);
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

            // On s'assure que la personne créant l'évènement soit parmis les organisateurs
            if (!in_array($this->getUser(), $event->getOrganisers()->toArray())) {
                $event->addOrganiser($this->getUser());
            }

            // On ajoute les organisateurs à la liste des participants
            foreach ($event->getOrganisers()->toArray() as $organiser) {
                $event->addParticipant($organiser);
            }


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
     * @Route("/informations/{event}", name="event_details")
     *
     * @param Event $event
     * @return void
     */
    public function details(Event $event)
    {

        return $this->render("event/details.html.twig", [
            "event" => $event
        ]);

    }


    /**
     * @Route("/editer/{id}", name="event_update")
     * @param Request $request
     * @param Event $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Event $event)
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash("success", "L'évènement a bien été modifié");
            return $this->redirectToRoute("event_index");
        }

        return $this->render("event/update.html.twig", [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/rejoindre/{event}", name="event_join")
     * @IsGranted("ROLE_USER")
     *
     * @param Event $event
     * @return void
     */
    public function join(Event $event)
    {

        $event->addParticipant($this->getUser());
        $this->manager->flush();

        $this->addFlash("success", "Vous avez rejoint l'évènement {$event->getTitle()}");
        return $this->redirectToRoute("event_details", ["event" => $event->getId()]);

    }

    /**
     * @Route("/quitter/{event}", name="event_quit")
     * @IsGranted("ROLE_USER")
     *
     * @param Event $event
     * @return void
     */
    public function quit(Event $event)
    {

        $event->removeParticipant($this->getUser());
        $this->manager->flush();

        $this->addFlash("success", "Vous avez quitté l'évènement {$event->getTitle()}");
        return $this->redirectToRoute("event_details", ["event" => $event->getId()]);

    }


}
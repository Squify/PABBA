<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventController
 * @package App\Controller
 * @Route("/evenement")
 */
class EventController extends AbstractController
{
    private EntityManagerInterface $manager;
    private EventRepository        $eventRepository;

    public function __construct(EntityManagerInterface $manager, EventRepository $eventRepository)
    {
        $this->manager         = $manager;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @Route("", name="event_index")
     */
    public function index()
    {
        return $this->render("event/index.html.twig");
    }

    /**
     * @Route("/search", name="event_search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $form = $this->createForm(EventSearchType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $events = $this->eventRepository->search($form->getData());
        }else{
            $events = $this->eventRepository->search([]);
        }

        dump($events);

        return $this->json([
            'form' => $this->render("components/_search_form.html.twig", [
                'form' => $form->createView(),
            ])->getContent(),
            'content' => $this->render("event/_rows.html.twig", [
                'events' => $events,
            ])->getContent()
        ]);

    }

    /**
     * @Route("/creer", name="event_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $event = new Event();
        $form  = $this->createForm(EventType::class, $event);
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
            'form'  => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/{event}", name="event_details")
     *
     * @param Event $event
     * @return Response
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
     * @return RedirectResponse|Response
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
            'form'  => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="event_delete")
     * @param Event $event
     * @return RedirectResponse
     */
    public function delete(Event $event)
    {
        if(!$event->getOrganisers()->contains($this->getUser())){
            $this->addFlash("error", "Vous ne pouvez pas supprimer un événement dont vous n'êtes pas organisateur");
        }else{
            $this->manager->remove($event);
            $this->manager->flush();
            $this->addFlash("success", "L'évènement a bien été supprimé");
        }


        return $this->redirectToRoute("event_index");
    }

    /**
     * @Route("/rejoindre/{event}", name="event_join")
     * @IsGranted("ROLE_USER")
     *
     * @param Event $event
     * @return RedirectResponse
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
     * @return RedirectResponse
     */
    public function quit(Event $event)
    {
        $event->removeParticipant($this->getUser());
        $this->manager->flush();

        $this->addFlash("success", "Vous avez quitté l'évènement {$event->getTitle()}");

        return $this->redirectToRoute("event_details", ["event" => $event->getId()]);
    }

}

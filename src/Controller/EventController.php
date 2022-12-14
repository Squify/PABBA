<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventSearchType;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Warning;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $events = $this->eventRepository->search($form->getData());
        } else {
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

            // On s'assure que la personne cr??ant l'??v??nement soit parmis les organisateurs
            if (!in_array($this->getUser(), $event->getOrganisers()->toArray())) {
                $event->addOrganiser($this->getUser());
            }

            // On ajoute les organisateurs ?? la liste des participants
            foreach ($event->getOrganisers()->toArray() as $organiser) {
                $event->addParticipant($organiser);
            }

            $this->manager->persist($event);
            $this->manager->flush();
            $this->addFlash("success", "L'??v??nement a bien ??t?? cr????");

            return $this->redirectToRoute("event_index");
        }

        return $this->render("event/create.html.twig", [
            'form'  => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/gestion", name="event_moderation")
     * @IsGranted("ROLE_MODERATOR")
     */
    public function moderation()
    {

        return $this->render("event/moderation.html.twig", [
            "events" => $this->eventRepository->getEventsToModerate()
        ]);
    }


    /**
     * @Route("/valider", name="event_validate")
     * @IsGranted("ROLE_MODERATOR")
     *
     * @param Request $request
     * @return void
     */
    public function validate(Request $request)
    {

        // dump($request);

        $event = $this->eventRepository->find($request->request->get('event'));

        $event->setIsPublished(true)
            ->setIsRefused(false);

        $this->manager->flush();

        $this->addFlash("success", "L'??v??nement a ??t?? valid?? et va ??tre publi??");
        return $this->redirectToRoute("event_moderation");
    }

    /**
     * @Route("/refuser", name="event_refuse")
     * @IsGranted("ROLE_MODERATOR")
     *
     * @param Request $request
     * @return void
     */
    public function refuse(Request $request)
    {
        // dd($request);
        $event = $this->eventRepository->find($request->request->get('event'));

        $event->setIsRefused(true)
            ->setRefusedComment($request->request->get("text"));

        $this->manager->flush();

        $this->addFlash("success", "L'??v??nement a bien ??t?? refus??");
        return $this->redirectToRoute("event_moderation");
    }

    /**
     * @Route("/{event}", name="event_details")
     *
     * @param Event $event
     * @return Response
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, Event $event)
    {
        if (!in_array($this->getUser(), $event->getOrganisers()->toArray()))
        {
            $this->addFlash('error', "Vous ne faites pas partis des organisateurs de l'??v??nement");
            return $this->redirectToRoute('event_index');
        }
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash("success", "L'??v??nement a bien ??t?? modifi??");

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
        if (!$event->getOrganisers()->contains($this->getUser())) {
            $this->addFlash("error", "Vous ne pouvez pas supprimer un ??v??nement dont vous n'??tes pas organisateur");
        } else {
            $this->manager->remove($event);
            $this->manager->flush();
            $this->addFlash("success", "L'??v??nement a bien ??t?? supprim??");
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

        $this->addFlash("success", "Vous avez rejoint l'??v??nement {$event->getTitle()}");

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

        $this->addFlash("success", "Vous avez quitt?? l'??v??nement {$event->getTitle()}");

        return $this->redirectToRoute("event_details", ["event" => $event->getId()]);
    }
}

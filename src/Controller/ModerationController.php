<?php

namespace App\Controller;

use App\Entity\Moderation;
use App\Entity\ModerationMessage;
use App\Entity\Rent;
use App\Repository\ModerationRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route (path="/moderation")
 **/
class ModerationController extends AbstractController
{
    private ModerationRepository   $moderationRepository;
    private EntityManagerInterface $em;
    private UserRepository         $userRepository;
    /**
     * @var MailerService
     */
    private MailerService $mailerService;

    /**
     * ModerationController constructor.
     * @param ModerationRepository $moderationRepository
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param MailerService $mailerService
     */
    public function __construct(
        ModerationRepository $moderationRepository,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        MailerService $mailerService
    ) {
        $this->moderationRepository = $moderationRepository;
        $this->em                   = $em;
        $this->userRepository       = $userRepository;
        $this->mailerService        = $mailerService;
    }

    /**
     * @Route("/creer/{rent}", name="moderation_create")
     * @param Rent $rent
     * @return Response
     */
    public function create(Rent $rent): Response
    {
        $moderation = $this->moderationRepository->findOneByRent($rent);

        if ($moderation) {
            $this->addFlash('error', "Une modération est déjà en cours pour cette location");
        } else {
            $moderator = $this->getModerator();

            if (!$moderator) {
                $this->addFlash("error", "Il ne semble pas y avoir de modérateur disponible. Nous sommes désolé");

                return $this->redirectToRoute('index');
            }

            $moderation = new Moderation();
            $moderation->setModerator($moderator)
                ->setRent($rent);
            $this->em->persist($moderation);
            $this->em->flush();

            $this->addFlash("success", "Votre espace de modération a bien été créé");
        }

        return $this->redirectToRoute('moderation_show', ['moderation' => $moderation->getId()]);
    }

    /**
     * @Route("/cloturer/{moderation}", name="moderation_delete")
     * @param Moderation $moderation
     * @return Response
     */
    public function delete(Moderation $moderation): Response
    {
        if (!$this->canAccess($moderation)) {
            throw new UnauthorizedHttpException('', "Vous n'êtes pas autorisé à accéder à cette modération");
        }

        $this->em->remove($moderation);
        $this->em->flush();

        $this->addFlash("success","La modération a bien été fermé");

        dump('TODO : redirect');
        die;
        return $this->redirectToRoute('moderation_show', ['moderation' => $moderation->getId()]);
    }

    /**
     * @Route(path="/traiter/{moderation}", name="moderation_show")
     * @param Moderation $moderation
     * @return Response
     */
    public function show(Moderation $moderation)
    {
        if (!$this->canAccess($moderation)) {
            throw new UnauthorizedHttpException('', "Vous n'êtes pas autorisé à accéder à cette modération");
        }

        return $this->render('rent/moderation/show.html.twig', [
            'moderation' => $moderation
        ]);
    }

    /**
     * @Route(path="/message/{moderation}", name="moderation_message_add", methods={"POST"})
     * @param Request $request
     * @param Moderation $moderation
     * @return JsonResponse
     */
    public function addMessage(Request $request, Moderation $moderation)
    {
        if (!$this->canAccess($moderation)) {
            throw new UnauthorizedHttpException('', "Vous n'êtes pas autorisé à accéder à cette modération");
        }

        $message = new ModerationMessage();
        $message->setContent($request->request->get('message'))
            ->setModeration($moderation)
            ->setCreatedAt(new \DateTime('now'))
            ->setWriter($this->getUser());
        $this->em->persist($message);
        $moderation->addModerationMessage($message);
        $this->em->flush();

        $now = new \DateTime('now');
        foreach ($moderation->getUsers() as $user) {
            $diff = $now->diff($user->getLastLogin());
            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;
            if ($user->getId() !== $this->getUser()->getId() && $minutes >= 5) {
                $this->mailerService->sendModerationNotification($moderation, $user);
            }
        }

        return new JsonResponse($this->renderView('rent/moderation/_messages.html.twig', [
            'moderation' => $moderation
        ]), Response::HTTP_OK);
    }

    /**
     * @Route(path="/message/{moderation}/read", name="moderation_message_read", methods={"GET"})
     * @param Moderation $moderation
     * @return JsonResponse
     */
    public function readMessages(Moderation $moderation)
    {
        if (!$this->canAccess($moderation)) {
            throw new UnauthorizedHttpException('', "Vous n'êtes pas autorisé à accéder à cette modération");
        }

        return new JsonResponse($this->renderView('rent/moderation/_messages.html.twig', [
            'moderation' => $moderation
        ]), Response::HTTP_OK);
    }

    private function getModerator()
    {
        $moderators = $this->userRepository->findByRole('ROLE_MODERATOR');

        if (!$moderators || count($moderators) == 0) {
            return null;
        }

        $data = [];
        foreach ($moderators as $moderator) {
            $data[] = [
                'm' => $moderator,
                'c' => $moderator->getModerations()->count()
            ];
        }

        uasort($data, [ModerationController::class, 'sortMod']);

        return array_shift($data)['m'];
    }

    public static function sortMod($a, $b)
    {
        $a = $a['c'];
        $b = $b['c'];
        if ($a == $b) {
            return 0;
        }

        return ($a < $b) ? -1 : 1;
    }

    private function canAccess(Moderation $moderation)
    {
        $userId = $this->getUser()->getId();

        return $moderation->getModerator()->getId() === $userId || $moderation->getRent()->getRenter()->getId() === $userId || $moderation->getRent()->getOwner()->getId() === $userId;
    }
}

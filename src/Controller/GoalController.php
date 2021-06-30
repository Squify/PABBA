<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Entity\Reward;
use App\Entity\User;
use App\Repository\GoalRepository;
use App\Repository\ItemRepository;
use App\Repository\RentRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/objectifs")
 */
class GoalController extends AbstractController
{
    /**
     * @var GoalRepository
     */
    private GoalRepository $goalRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * RewardsController constructor.
     * @param GoalRepository $goalRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(GoalRepository $goalRepository, EntityManagerInterface $em)
    {
        $this->goalRepository = $goalRepository;
        $this->em = $em;
    }

    /**
     * @Route("", name="goal_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        if($request->query->get('flash', false) === "1"){
            $this->addFlash('success', 'Vous allez recevoir votre récompense');
        }

        $goals = [];
        /** @var User $user */
        $user = $this->getUser();

        foreach ($this->goalRepository->findByActive(true) as $goal) {
            if (!$user->getGoals()->contains($goal)) {
                $goals[] = [
                    'reward'     => $goal->getReward(),
                    'goal'       => $goal,
                    'advance'    => $user->{$goal->getType()}()->count(),
                    'percentage' => ($user->{$goal->getType()}()->count() * 100) / $goal->getObjective()
                ];
            }
        }

        return $this->render('goals/list.html.twig', [
            'goals' => $goals
        ]);
    }

    /**
     * @Route("/take/{goal}", name="user_take_reward")
     * @param Goal $goal
     * @param MailerService $mailerService
     * @return RedirectResponse
     */
    public function take(Goal $goal, MailerService $mailerService)
    {
        /** @var User $user */
       $user = $this->getUser();
       $user->addGoal($goal);
       $this->em->flush();
       $mailerService->sendReward($goal, $user);
       return $this->redirectToRoute('goal_index', ['flash' => true]);
    }

}

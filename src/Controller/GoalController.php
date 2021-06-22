<?php

namespace App\Controller;

use App\Repository\GoalRepository;
use App\Repository\ItemRepository;
use App\Repository\RentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * RewardsController constructor.
     * @param GoalRepository $goalRepository
     */
    public function __construct(GoalRepository $goalRepository)
    {
        $this->goalRepository = $goalRepository;
    }

    /**
     * @Route("", name="goal_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $goals = [];
        $user  = $this->getUser();

        foreach ($this->goalRepository->findByActive(true) as $goal) {
            $goals[] = [
                'reward'     => $goal->getReward(),
                'goal'       => $goal,
                'advance'    => $user->{$goal->getType()}()->count(),
                'percentage' => ($user->{$goal->getType()}()->count() * 100) / $goal->getObjective()
            ];
        }

        return $this->render('goals/list.html.twig', [
            'goals' => $goals
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Moderation;
use App\Entity\Rent;
use App\Repository\ModerationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route (path="/moderation")
 **/
class ModerationController extends AbstractController
{
    /**
     * @var ModerationRepository
     */
    private ModerationRepository $moderationRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * ModerationController constructor.
     * @param ModerationRepository $moderationRepository
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     */
    public function __construct(ModerationRepository $moderationRepository, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->moderationRepository = $moderationRepository;
        $this->em                   = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/creer/{rent}", name="moderation_create")
     * @param Rent $rent
     * @return Response
     */
    public function index(Rent $rent = null): Response
    {
        $moderation = $this->moderationRepository->findOneByRent($rent);

        if ($moderation) {
            $this->addFlash('error', "Une modération est déjà en cours pour cette location");
        }else{
            $moderation = new Moderation();
            $moderation->setModerator($this->getModerator())
                ->setRent($rent);
            $this->em->persist($moderation);
            $this->em->flush();

            $this->addFlash("success", "Votre espace de modération a bien été créé");
        }

        return $this->redirectToRoute('moderation_show', ['moderation' => $moderation->getId()]);

    }

    /**
     * @Route(path="/taiter/{moderation}", name="moderation_show")
     * @param Moderation $moderation
     */
    public function show(Moderation $moderation)
    {
        dump($moderation);
        die;
    }

    private function getModerator(){
        $moderators = $this->userRepository->findByRole('ROLE_MODERATOR');

        if(!$moderators || count($moderators) == 0){
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

    public static function sortMod($a, $b){
        $a = $a['c'];
        $b = $b['c'];
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}

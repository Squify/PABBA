<?php

namespace App\Controller\User;

use App\Event\User\UserChangePasswordEvent;
use App\Form\User\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProfileController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * ProfileController constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/mon-compte", name="user.profile.edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash("success", "Félicitation, votre profle a bien été modifié");
        }

        return $this->render('profile/myaccount.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/changement-mot-de-passe", name="user.profile.change_password")
     */
    public function changePassword(){
        $user = $this->getUser();
        $user->setToken(md5(uniqid()));
        $this->em->flush();
        $this->eventDispatcher->dispatch(new UserChangePasswordEvent($user));
        $this->addFlash("success", "Un email vient d'être envoyé à " . $user->getEmail() . " pour modifier votre mot de passe");
        return $this->redirectToRoute('user.profile.edit');
    }
}

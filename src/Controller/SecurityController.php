<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\User\UserChangePasswordConfirmEvent;
use App\Event\User\UserChangePasswordEvent;
use App\Form\Security\ChangePasswordType;
use App\Form\Security\ResetPasswordType;
use App\Form\User\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SecurityController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * SecurityController constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        UserPasswordEncoderInterface $userPasswordEncoder
    )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @Route("/connexion", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/recuperation-mot-de-passe", name="getResetPassword")
     * @param Request $request
     * @return Response
     */
    public function getResetPassword(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy([
                'email' => $form->getData()['email']
            ]);

            if ($user) {
                $user->setToken(md5(uniqid()));
                $this->em->flush();
                $this->eventDispatcher->dispatch(new UserChangePasswordEvent($user));
                $this->addFlash("success", "Un email vient d'être envoyé à " . $user->getEmail() . " pour changer le mot de passe");
            } else {
                $form->get('email')->addError(new FormError('Cet identifiant ne correspond à aucun utilisateur'));
            }
        }

        return $this->render('security/getResetPassword.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param string $token
     * @param int $id
     * @return Response
     * @Route("/resetPassword/{token}/{id}", name="resetPassword")
     */
    public function resetPassword(Request $request, string $token, int $id)
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy([
            "id"    => $id,
            "token" => $token
        ]);

        $form = $this->createForm(ChangePasswordType::class);

        if ($user) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user
                    ->setPassword($this->userPasswordEncoder->encodePassword($user, $form->getData()["password"]))
                    ->setToken(null)
                    ->setEnable(true);

                $this->em->flush();

                $this->eventDispatcher->dispatch(new UserChangePasswordConfirmEvent($user));
                $this->addFlash("success", $user->getLastLogin() !== null ? "Votre mot de passe à bien été modifié" : "Votre compte a bien été activé");

                return $this->redirectToRoute("app_login");
            }
        } else {
            $this->addFlash("error", "Votre lien n'est plus valable. Veuillez en demander un autre");

            return $this->redirectToRoute("getResetPassword");
        }

        return $this->render('security/resetPassword.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route(path="/inscription", name="register")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request){
        $user = new User();
        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user
                ->setRoles(['ROLE_USER'])
                ->setToken(md5(uniqid()))
                ->setEnable(false)
                ->setPassword(md5(uniqid()));
            $this->em->persist($user);
            $this->em->flush();
            $this->eventDispatcher->dispatch(new UserChangePasswordEvent($user));

            $this->addFlash("success", "Félicitation, c'est presque terminé. Un email vient d'être envoyé à " . $user->getEmail() . " pour créer votre mot de passe");

        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

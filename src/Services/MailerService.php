<?php

namespace App\Services;

use App\Entity\Goal;
use App\Entity\Moderation;
use App\Entity\Rent;
use App\Entity\Reward;
use App\Entity\Tutorial;
use App\Entity\User;
use App\Repository\UserRepository;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService
{
    private Environment $environment;
    private string      $noreply;
    private Client      $client;
    private UserRepository $userRepository;

    /**
     * MailerService constructor.
     * @param Environment $environment
     * @param string $mailjetSecret
     * @param string $mailjetPublic
     * @param string $noreply
     * @param UserRepository $userRepository
     */
    public function __construct(
        Environment $environment,
        string $mailjetSecret,
        string $mailjetPublic,
        string $noreply,
        UserRepository $userRepository
    )
    {
        $this->environment = $environment;
        $this->noreply     = $noreply;
        $this->client      = new Client($mailjetPublic, $mailjetSecret, true, ['version' => 'v3']);
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @param bool $firstLogin
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function resetPassword(User $user, bool $firstLogin = false)
    {
        $subject = $firstLogin ? "Configuration de votre compte" : "RĂ©initialisation de votre mot de passe";
        $config  = [
            'FromEmail' => $this->noreply,
            'To'     => $user->getEmail(),
            'Subject'   => $subject,
            'Html-part' => $this->environment->render('emails/resetPassword.html.twig', [
                "user"    => $user,
                'subject' => $subject,
                "firstLogin" => $firstLogin

            ])
        ];

        return $this->client->post(Resources::$Email, ['body' => $config]);
    }

    /**
     * @param User $user
     * @param boolean $firstLogin
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function changePasswordConfirm(User $user, bool $firstLogin = false)
    {
        $subject = $firstLogin ? "Votre comptĂ© a Ă©tĂ© activĂ©" :  "Confirmation de changement de mot de passe";
        $config  = [
            'FromEmail' => $this->noreply,
            'To'     => $user->getEmail(),
            'Subject'   => $subject,
            'Html-part' => $this->environment->render('emails/resetPasswordConfirm.html.twig', [
                "user"    => $user,
                'subject' => $subject,
                "firstLogin" => $firstLogin
            ])
        ];

        return $this->client->post(Resources::$Email, ['body' => $config]);
    }

    public function sendBaseEmail(string $subject, string $message, string $to = null )
    {
        $config  = [
            'FromEmail' => $this->noreply,
            'To'     => $to ? $to : $_ENV['MAIL_SUPPORT'],
            'Subject'   => $subject,
            'Html-part' => $message
        ];

        return $this->client->post(Resources::$Email, ['body' => $config]);
    }

    /**
     * @param Tutorial $tutorial
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendAlertTutorial(Tutorial $tutorial)
    {
        $config  = [
            'FromEmail' => $this->noreply,
            'To'     => $tutorial->getUser()->getEmail(),
            'Subject'   => "Votre tutoriel n'est pas conforme",
            'Html-part' => $this->environment->render('emails/alertTutorial.html.twig',
            [
                'subject' => 'Votre tutoriel n\'est pas conforme',
                'tutorial' => $tutorial,
            ])
        ];

        return $this->client->post(Resources::$Email, ['body' => $config]);
    }

    public function sendModerationNotification(Moderation $moderation, User $user)
    {
        $config  = [
            'FromEmail' => $this->noreply,
            'To'     => $user->getEmail(),
            'Subject'   => "Un nouveau message vous a Ă©tĂ© adressĂ©",
            'Html-part' => $this->environment->render('emails/moderationNotification.html.twig',
                [
                    'subject' => "Un nouveau message vous a Ă©tĂ© adressĂ©",
                    'moderation' => $moderation,
                    'user' => $user,
                ])
        ];

        return $this->client->post(Resources::$Email, ['body' => $config]);
    }

    public function sendReward(Goal $goal, User $user){
        $config  = [
            'FromEmail' => $this->noreply,
            'To'     => $user->getEmail(),
            'Subject'   => "Une nouvelle rĂ©compense est disponible",
            'Html-part' => $this->environment->render('emails/reward.html.twig',
                [
                    'subject' => "Une nouvelle rĂ©compense est disponible",
                    'goal' => $goal,
                    'user' => $user,
                ])
        ];

        return $this->client->post(Resources::$Email, ['body' => $config]);
    }

}

<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Mailjet\Client;
use Mailjet\Resources;
use Mailjet\Response;
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
        $subject = $firstLogin ? "Configuration de votre compte" : "Réinitialisation de votre mot de passe";
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
        $subject = $firstLogin ? "Votre compté a été activé" :  "Confirmation de changement de mot de passe";
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

}

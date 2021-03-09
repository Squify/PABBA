<?php

namespace App\EventSubscriber;

use App\Event\User\UserChangePasswordEvent;
use App\Event\User\UserChangePasswordConfirmEvent;
use App\Services\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerService
     */
    private MailerService $mailerService;

    /**
     * MailerSubscriber constructor.
     * @param MailerService $mailerService
     */
    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function onUserChangePassword(UserChangePasswordEvent $event)
    {
        try {
            $this->mailerService->resetPassword($event->getUser(), $event->getUser()->getLastLogin() === null);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }

    public function onUserChangePasswordConfirm(UserChangePasswordConfirmEvent $event)
    {
        try {
            $this->mailerService->changePasswordConfirm($event->getUser(), $event->getUser()->getLastLogin() === null);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            UserChangePasswordEvent::class        => 'onUserChangePassword',
            UserChangePasswordConfirmEvent::class => 'onUserChangePasswordConfirm',
        ];
    }
}

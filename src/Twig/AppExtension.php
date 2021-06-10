<?php
namespace App\Twig;

use App\Entity\Render;
use App\Entity\Rent;
use DateTime;
use Mobile_Detect;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('isMobile', [$this, 'isMobile']),
            new TwigFunction('isTablet', [$this, 'isTablet']),
            new TwigFunction('rentIsOld', [$this, 'rentIsOld']),
            new TwigFunction('rentReturned', [$this, 'rentReturned']),
            new TwigFunction('isToday', [$this, 'isToday']),
        ];
    }

    public function isMobile()
    {
        $detect = new Mobile_Detect;
        return $detect->isMobile();
    }

    public function isTablet()
    {
        $detect = new Mobile_Detect;
        return $detect->isTablet();
    }

    public function rentIsOld(Rent $rent)
    {
        $today = new \DateTime();
        return  $rent->getReturnAt() ? $rent->getReturnAt()->getTimestamp() < $today->getTimestamp() : null;
    }

    public function rentReturned(Rent $rent)
    {

        /** @var Render */
        $render = $rent->getRenders()->get(0);

        return $render && $render->getIsValid();

    }

    public function isToday(DateTime $date)
    {
        // On instancie un DateTime à aujourd'hui
        $today = new DateTime();
        // On set l'heure à 23h59 pour être au dernier moment de la journée
        $today->setTime(23, 59, 59);

        // On compte le nombre de jours de différence
        $diff = $today->diff($date)->format("%R%a");

        switch ($diff) {
            case 0:
                // C'est aujourd'hui
                return 0;
                break;

            case $diff > 0:
                // C'est pas encore passé
                return 1;
                break;

            case $diff < 0:
                // C'est déjà passé
                return 2;
                break;

        }

    }

}

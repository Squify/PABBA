<?php
namespace App\Twig;

use App\Entity\Render;
use App\Entity\Rent;
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
        return $rent->getReturnAt()->getTimestamp() < $today->getTimestamp();
    }

    public function rentReturned(Rent $rent)
    {

        /** @var Render */
        $render = $rent->getRenders()->get(0);
        
        return $render && $render->getIsValid();

    }
}

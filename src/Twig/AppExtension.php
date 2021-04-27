<?php
namespace App\Twig;

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
}
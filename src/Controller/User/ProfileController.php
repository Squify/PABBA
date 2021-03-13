<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route(path="/mon-compte", name="user.profile.edit")
     * @param Request $request
     */
    public function edit(Request $request){
        $form = $this->createForm('');
    }
}

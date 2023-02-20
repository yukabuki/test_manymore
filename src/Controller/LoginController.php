<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
	    $error = $authenticationUtils->getLastAuthenticationError();
	    $lastLogin = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'title' => 'Login',
	        'error' => $error,
	        'last_login' => $lastLogin,
        ]);
    }
}

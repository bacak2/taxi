<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route(
     *     "/login",
     *     name="login"
     * )
     */
    public function loginAction(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        $form = $this->createForm(LoginForm::class, array(
            'username' => $lastUsername
        ));

        return $this->render('@App/security/login.html.twig', array(
            'error' => $error,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(
     *     "/public/login",
     *     name="route_public_login"
     * )
     */
    public function publicLoginAction(AuthenticationUtils $utils)
    {
        dump($utils->getLastAuthenticationError());

        return $this->render('@App/public/security/login.html.twig', []);
    }
}

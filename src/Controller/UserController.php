<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginFormType;
use App\Security\Token\ApiToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/login', name: 'user_login', methods: ['GET', 'POST'])]
    public function loginAction(): RedirectResponse|Response
    {
        $token = $this->container->get('security.token_storage')->getToken();

        if ($token instanceof ApiToken && $token->isAuthenticated()) {
            return $this->redirectToRoute('index');
        }

        return $this
            ->render(
                'security/login.html.twig',
                [
                    'form' => $this->createForm(LoginFormType::class)->createView(),
                ]
            );
    }

    #[Route('/logout', name: 'user_logout', methods: ['GET'])]
    public function logoutAction(): RedirectResponse|Response
    {
        $this->container->get('security.token_storage')->setToken(null);

        return $this->redirectToRoute('user_login');
    }
}

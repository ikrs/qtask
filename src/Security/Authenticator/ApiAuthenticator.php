<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Contract\Service\AuthenticationServiceInterface;
use App\Form\LoginFormType;
use App\Security\Passport\ApiTokenPassport;
use App\Security\Token\ApiToken;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    private AuthenticationServiceInterface $authenticationService;
    private FormFactoryInterface $formFactory;
    private RouterInterface $router;
    private SessionInterface $session;

    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->authenticationService = $authenticationService;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @param ApiTokenPassport $passport
     * @param string $firewallName
     * @return TokenInterface
     */
    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
    {
        return new ApiToken(
            $passport->getTokenData()
        );
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): ?bool
    {
        return $this->isLogin($request);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): PassportInterface
    {
        $form = $this->formFactory->create(LoginFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = $data['email'];
            $password = $data['password'];

            try {
                $tokenData = $this
                    ->authenticationService
                    ->getToken($email, $password);

                return new ApiTokenPassport($tokenData);
            } catch (AccessDeniedHttpException) {
                //
            }
        }

        throw new AuthenticationException();
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('author_index'));
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($this->session instanceof Session) {
            $this->session->getFlashBag()->add('warning', 'Invalid email or password');
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('user_login'));
    }

    private function isLogin(Request $request): bool
    {
        return $request->attributes->get('_route') === 'user_login' &&
            $request->isMethod('POST');
    }
}

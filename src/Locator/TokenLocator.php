<?php

declare(strict_types=1);

namespace App\Locator;

use App\Contract\Security\ApiTokenInterface;
use App\Contract\Service\AuthenticationServiceInterface;
use App\Security\Token\ApiToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class TokenLocator
{
    private Security $security;
    private TokenStorageInterface $tokenStorage;
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(
        Security $security,
        TokenStorageInterface $tokenStorage,
        AuthenticationServiceInterface $authenticationService
    ) {
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
        $this->authenticationService = $authenticationService;
    }

    public function locate(): ?string
    {
        $token = $this->security->getToken();

        if ($token instanceof ApiTokenInterface) {
            try {
                $tokenData = $token->getTokenData();

                if ($tokenData->canUse()) {
                    return $tokenData->getToken();
                } elseif ($tokenData->canRefresh()) {
                    $newDataToken = $this->authenticationService->refreshToken($tokenData->getRefreshToken());

                    $newToken = new ApiToken($newDataToken);

                    $this->tokenStorage->setToken($newToken);
                }
            } catch (AccessDeniedHttpException) {
            }
        }

        return null;
    }
}

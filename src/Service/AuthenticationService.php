<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Http\ClientInterface;
use App\Contract\Service\AuthenticationServiceInterface;
use App\Contract\Data\TokenDataInterface;
use App\Exception\Client\RequestException;
use App\Factory\RequestFactory;
use App\Factory\TokenDataFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthenticationService implements AuthenticationServiceInterface
{
    private ClientInterface $client;
    private RequestFactory $requestFactory;
    private TokenDataFactory $tokenDataFactory;

    public function __construct(
        ClientInterface $client,
        RequestFactory $requestFactory,
        TokenDataFactory $tokenDataFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->tokenDataFactory = $tokenDataFactory;
    }

    /**
     * @inheritDoc
     */
    public function getToken(string $email, string $password): TokenDataInterface
    {
        $request = $this->requestFactory->create(
            'POST',
            'token',
            false,
            [
                'email' => $email,
                'password' => $password,
            ]
        );

        try {
            $request = $this->client->request($request);
            return $this->tokenDataFactory->create(
                $request
            );
        } catch (RequestException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function refreshToken(string $token): TokenDataInterface
    {
        $request = $this->requestFactory->create(
            'GET',
            'token/refresh/' . $token,
            false,
        );

        try {
            $request = $this->client->request($request);
            return $this->tokenDataFactory->create(
                $request
            );
        } catch (RequestException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }
    }
}

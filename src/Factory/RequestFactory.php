<?php

declare(strict_types=1);

namespace App\Factory;

use App\Action\Http\Request;
use App\Contract\Factory\RequestFactoryInterface;
use App\Exception\InvalidTokenException;
use App\Locator\TokenLocator;

class RequestFactory implements RequestFactoryInterface
{
    private TokenLocator $locator;

    public function setLocator(TokenLocator $locator): void
    {
        $this->locator = $locator;
    }

    /**
     * @throws InvalidTokenException
     */
    public function create(
        string $method,
        string $uri,
        bool $authenticated,
        array $body = [],
        array $headers = [],
    ): Request {
        $request = new Request();

        $request
            ->withMethod($method)
            ->withUri($uri)
            ->withBody($body)
            ->withHeaders($headers);

        if ($authenticated) {
            $token = $this->locator->locate();

            if ($token === null) {
                throw new InvalidTokenException();
            }

            $request->withHeader('Authorization', 'Bearer ' . $token);
        }

        return $request;
    }
}

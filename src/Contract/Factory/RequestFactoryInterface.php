<?php

declare(strict_types=1);

namespace App\Contract\Factory;

use App\Action\Http\Request;
use App\Exception\InvalidTokenException;

interface RequestFactoryInterface
{
    /**
     * @throws InvalidTokenException
     */
    public function create(
        string $method,
        string $uri,
        bool $authenticated,
        array $body = [],
        array $headers = [],
    ): Request;
}

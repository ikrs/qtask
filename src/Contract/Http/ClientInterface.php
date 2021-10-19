<?php

declare(strict_types=1);

namespace App\Contract\Http;

use App\Action\Http\Request;
use App\Exception\Client\RequestException;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * @throws RequestException
     */
    public function request(Request $request): ResponseInterface;
}

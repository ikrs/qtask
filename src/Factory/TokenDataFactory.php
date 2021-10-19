<?php

declare(strict_types=1);

namespace App\Factory;

use App\Contract\Data\TokenDataInterface;
use App\Data\TokenData;
use Psr\Http\Message\ResponseInterface;
use DateTime;

class TokenDataFactory
{
    public function create(ResponseInterface $response): TokenDataInterface
    {
        $content = \json_decode($response->getBody()->getContents(), true);

        $user = $content['user'];

        return new TokenData(
            $user['first_name'] . ' ' . $user['first_name'],
            $user['email'],
            $content['token_key'],
            new DateTime($content['expires_at']),
            $content['refresh_token_key'],
            new DateTime($content['refresh_expires_at'])
        );
    }
}

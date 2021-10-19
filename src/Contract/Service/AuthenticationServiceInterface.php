<?php

declare(strict_types=1);

namespace App\Contract\Service;

use App\Contract\Data\TokenDataInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

interface AuthenticationServiceInterface
{
    /**
     * @throws AccessDeniedHttpException
     */
    public function getToken(string $email, string $password): TokenDataInterface;

    /**
     * @throws AccessDeniedHttpException
     */
    public function refreshToken(string $token): TokenDataInterface;
}

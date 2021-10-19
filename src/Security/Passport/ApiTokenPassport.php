<?php

declare(strict_types=1);

namespace App\Security\Passport;

use App\Contract\Data\TokenDataInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportTrait;

class ApiTokenPassport implements PassportInterface
{
    use PassportTrait;

    private TokenDataInterface $tokenData;

    public function __construct(TokenDataInterface $tokenData)
    {
        $this->tokenData = $tokenData;
    }

    public function getTokenData(): TokenDataInterface
    {
        return $this->tokenData;
    }
}

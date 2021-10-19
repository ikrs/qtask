<?php

declare(strict_types=1);

namespace App\Contract\Security;

use App\Contract\Data\TokenDataInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface ApiTokenInterface extends TokenInterface
{
    public function getTokenData(): TokenDataInterface;
}

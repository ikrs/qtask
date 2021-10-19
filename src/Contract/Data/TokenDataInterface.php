<?php

declare(strict_types=1);

namespace App\Contract\Data;

use DateTimeInterface;

interface TokenDataInterface
{
    public function canUse(): bool;

    public function canRefresh(): bool;

    public function getName(): mixed;

    public function getEmail(): string;

    public function getToken(): string;

    public function getTokenExpiresAt(): DateTimeInterface;

    public function getRefreshToken(): string;

    public function getRefreshTokenExpiresAt(): DateTimeInterface;
}

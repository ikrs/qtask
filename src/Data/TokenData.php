<?php

declare(strict_types=1);

namespace App\Data;

use App\Contract\Data\TokenDataInterface;
use DateTimeInterface;
use DateTime;

class TokenData implements TokenDataInterface
{
    private string $name;
    private string $email;
    private string $token;
    private DateTimeInterface $tokenExpiresAt;
    private string $refreshToken;
    private DateTimeInterface $refreshTokenExpiresAt;

    public function __construct(
        string $name,
        string $email,
        string $token,
        DateTimeInterface $tokenExpiresAt,
        string $refreshToken,
        DateTimeInterface $refreshTokenExpiresAt
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
        $this->tokenExpiresAt = $tokenExpiresAt;
        $this->refreshToken = $refreshToken;
        $this->refreshTokenExpiresAt = $refreshTokenExpiresAt;
    }

    public function canUse(): bool
    {
        $expires = $this->tokenExpiresAt;

        $now = new DateTime();

        $now->setTimezone($expires->getTimezone());

        $now->modify('+60 seconds');

        return $expires > $now;
    }

    public function canRefresh(): bool
    {
        $expires = $this->refreshTokenExpiresAt;

        $now = new DateTime();

        $now->setTimezone($expires->getTimezone());

        $now->modify('+60 seconds');

        return $expires > $now;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenExpiresAt(): DateTimeInterface
    {
        return $this->tokenExpiresAt;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getRefreshTokenExpiresAt(): DateTimeInterface
    {
        return $this->refreshTokenExpiresAt;
    }

    public function __serialize(): array
    {
        return [
            $this->name,
            $this->email,
            $this->token,
            $this->tokenExpiresAt,
            $this->refreshToken,
            $this->refreshTokenExpiresAt,
        ];
    }

    public function __unserialize(array $data): void
    {
        [
            $this->name,
            $this->email,
            $this->token,
            $this->tokenExpiresAt,
            $this->refreshToken,
            $this->refreshTokenExpiresAt,
        ] = $data;
    }
}

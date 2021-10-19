<?php

declare(strict_types=1);

namespace App\Security\Token;

use App\Contract\Data\TokenDataInterface;
use App\Contract\Security\ApiTokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ApiToken extends AbstractToken implements ApiTokenInterface
{
    private TokenDataInterface $tokenData;

    public function __construct(
        TokenDataInterface $tokenData,
        array $roles = ['ROLE_USER']
    ) {
        parent::__construct($roles);

        $this->tokenData = $tokenData;

        $this->setUser($tokenData->getName() . ' ( ' . $tokenData->getEmail() . ') ');

        $this->setAuthenticated(true);
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated()
    {
        $tokenData = $this->tokenData;

        if (!$tokenData->canUse() && !$tokenData->canRefresh()) {
            $this->setAuthenticated(false);
        }

        return parent::isAuthenticated();
    }

    public function getTokenData(): TokenDataInterface
    {
        return $this->tokenData;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials()
    {
        return [];
    }

    public function __serialize(): array
    {
        return [$this->tokenData, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [$this->tokenData, $parentData] = $data;

        $parentData = \is_array($parentData) ? $parentData : unserialize($parentData);

        parent::__unserialize($parentData);
    }
}

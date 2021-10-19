<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Factory\RequestFactoryInterface;
use App\Contract\Http\ClientInterface;

abstract class AbstractRepository
{
    protected ClientInterface $client;
    protected RequestFactoryInterface $requestFactory;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }
}

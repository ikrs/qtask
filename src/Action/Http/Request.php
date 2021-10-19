<?php

declare(strict_types=1);

namespace App\Action\Http;

class Request
{
    public const DIRECTION_ASC = 'ASC';
    public const DIRECTION_DESC = 'DESC';

    private string $method;
    private string $uri;
    private bool $collection = false;
    private array $body = [];
    private ?string $orderBy = null;
    private string $direction = self::DIRECTION_ASC;
    private int $limit = 10;
    private int $page = 1;

    private array $headers = [];

    public function withMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function withUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function withCollection(bool $collection): self
    {
        $this->collection = $collection;
        return $this;
    }

    public function withBody(array $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function withOrderBy(?string $order): self
    {
        $this->orderBy = $order;
        return $this;
    }

    public function withDirection(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    public function withLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function withPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function withHeader(string $key, mixed $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function isCollection(): bool
    {
        return $this->collection;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}

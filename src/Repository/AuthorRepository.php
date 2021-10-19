<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\AuthorRepositoryInterface;
use App\Exception\Client\RequestException;
use App\Exception\EntityNotFoundException;
use App\Model\Author;
use App\Model\AuthorCollection;

class AuthorRepository extends AbstractRepository implements AuthorRepositoryInterface
{
    public const URI = 'authors';

    /**
     * @throws RequestException
     */
    public function getMany(
        int $page = 1,
        int $limit = 20,
        ?string $orderBy = null
    ): AuthorCollection {
        $models = [];

        $request = $this->requestFactory->create(
            'GET',
            self::URI,
            true
        );

        $request
            ->withPage($page)
            ->withCollection(true)
            ->withLimit($limit);

        if ($orderBy !== null) {
            $request->withOrderBy($orderBy);
        }

        $authorsData = \json_decode($this->client->request($request)->getBody()->getContents(), true);

        $totalPages = (int)$authorsData['total_pages'] ?? 0;;

        if (is_iterable($authorsData)) {
            $items = $authorsData['items'] ?? [];

            foreach ($items as $authorData) {
                $models[] = Author::createFromArray($authorData);
            }
        }

        return new AuthorCollection($models, $totalPages);
    }

    /**
     * @inheritDoc
     */
    public function getOne(int $authorId): Author
    {
        $request = $this->requestFactory->create(
            'GET',
            self::URI . '/' . $authorId,
            true
        );

        try {
            $authorData = \json_decode($this->client->request($request)->getBody()->getContents(), true);

            if (is_array($authorData)) {
                return Author::createFromArray($authorData);
            }
        } catch (RequestException) {
        }

        throw new EntityNotFoundException('Author not found');
    }

    /**
     * @inheritDoc
     */
    public function remove(int $authorId): void
    {
        $request = $this->requestFactory->create(
            'DELETE',
            self::URI . '/' . $authorId,
            true
        );

        try {
            $this->client->request($request);
        } catch (RequestException) {
            throw new EntityNotFoundException('Author not found');
        }
    }
}

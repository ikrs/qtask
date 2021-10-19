<?php

declare(strict_types=1);

namespace App\Contract\Repository;

use App\Exception\CantSaveException;
use App\Exception\EntityNotFoundException;
use App\Model\Book;
use App\Model\BookCollection;

interface BookRepositoryInterface extends RepositoryInterface
{
    public function getMany(int $page = 1, int $limit = self::DEFAULT_LIMIT): BookCollection;

    /**
     * @throws EntityNotFoundException
     */
    public function getOne(int $bookId): Book;

    /**
     * @throws CantSaveException
     */
    public function save(Book $book): void;

    /**
     * @throws EntityNotFoundException
     */
    public function remove(int $bookId): void;
}

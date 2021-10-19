<?php

declare(strict_types=1);

namespace App\Model;

use ArrayIterator;

class Collection extends ArrayIterator
{
    private int $totalPages;

    public function __construct(array $data, int $totalPages, $flags = 0)
    {
        parent::__construct($data, $flags);

        $this->totalPages = $totalPages;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}

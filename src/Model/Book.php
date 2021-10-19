<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;
use DateTimeInterface;

class Book
{
    private ?int $id = null;
    private string $title = '';
    private DateTimeInterface $releaseDate;
    private string $isbn = '';
    private ?string $format = null;
    private int $numberOfPages = 0;
    private string $description = '';
    private ?Author $author = null;

    private ?int $authorId = null;

    public static function createFromArray(array $data): Book
    {
        $model = new Book();

        $model
            ->setId(array_key_exists('id', $data) ? (int) $data['id'] : null)
            ->setTitle($data['title'])
            ->setReleaseDate(new DateTime($data['release_date']))
            ->setIsbn($data['isbn'])
            ->setFormat($data['format'] ?? '')
            ->setNumberOfPages($data['number_of_pages'] ?? 0)
            ->setDescription($data['description'] ?? '');

        return $model;
    }

    public function toArray(): array
    {
        $export = [
            'title' => $this->getTitle(),
            'release_date' => $this->getReleaseDate()->format('c'),
            'isbn' => $this->getIsbn(),
            'format' => $this->getFormat(),
            'number_of_pages' => $this->getNumberOfPages(),
            'description' => $this->getDescription(),
        ];

        $bookId = $this->getId();

        if (null !== $bookId) {
            $export['id'] = $bookId;
        }

        if (null !== $this->authorId) {
            $export['author'] = [
                'id' => $this->authorId,
            ];
        }

        return $export;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $bookId): self
    {
        $this->id = $bookId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getFormat(): string
    {
        return (string) $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    public function setNumberOfPages(int $numberOfPages): self
    {
        $this->numberOfPages = $numberOfPages;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    public function setAuthorId(?int $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;
use DateTime;

class Author
{
    private ?int $id;
    private string $firstName;
    private string $lastName;
    private DateTimeInterface $birthday;
    private ?string $biography;
    private ?string $gender;
    private string $placeOfBirth;

    /**
     * @var Book[]
     */
    private iterable $books = [];

    public function __construct(
        ?int $id,
        string $firstName,
        string $lastName,
        DateTimeInterface $birthday,
        string $placeOfBirth,
        string $biography = '',
        string $gender = '',
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthday = $birthday;
        $this->biography = $biography;
        $this->gender = $gender;
        $this->placeOfBirth = $placeOfBirth;
    }

    public static function createFromArray(array $data): Author
    {
        $author = new Author(
            array_key_exists('id', $data) ? (int)$data['id'] : null,
            $data['first_name'],
            $data['last_name'],
            new DateTime($data['birthday']),
            $data['place_of_birth'] ?? '',
            $data['biography'] ?? '',
            $data['gender'] ?? '',
        );

        $books = $data['books'] ?? [];

        foreach ($books as $book) {
            $bookModel = Book::createFromArray($book);

            $bookModel->setAuthor($author);

            $author->addBook($bookModel);
        }

        return $author;
    }

    public function toArray(): array
    {
        $export = [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'birthday' => $this->getBirthday()->format('c'),
            'place_of_birth' => $this->getPlaceOfBirth(),
            'biography' => $this->getBiography(),
            'gender' => $this->getGender(),
        ];

        if ($this->id !== null) {
            $export['id'] = $this->id;
        }

        return $export;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getBirthday(): DateTimeInterface
    {
        return $this->birthday;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getPlaceOfBirth(): string
    {
        return $this->placeOfBirth;
    }

    public function addBook(Book $book): self
    {
        $this->books[] = $book;
        return $this;
    }

    public function hasBooks(): bool
    {
        return count($this->books) > 0;
    }
}

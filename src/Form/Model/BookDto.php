<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Book;

class BookDto
{

    /**
     * @Assert\NotBlank(
     * message = "El Título no puede estar en blanco."
     * )
     */
    public ?string $title = null;
    public ?string $description = null;
    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "La puntuación debe estar entre {{ min }} y {{ max }}."
     * )
     */
    public ?int $score = null;
    public ?string $base64Image = null;
    /** @var \App\Form\Model\CategoryDto[]|null */
    public ?array $categories = [];

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        return $dto;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBase64Image(): ?string
    {
        return $this->base64Image;
    }

    /**
     * @return \App\Form\Model\CategoryDto[]|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the value of score
     */
    public function getScore(): ?int
    {
        return $this->score;
    }
}

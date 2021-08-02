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
    public $title;
    public $base64Image;
    public $categories;

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        return $dto;
    }
}

<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Model\Exception\Book\BookNotFound;
use Symfony\Component\Uid\Uuid;

class GetBook
{

    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $id): ?Book
    {
        $book = $this->bookRepository->find(Uuid::fromString($id));
        if (!$book) {
            BookNotFound::throwException();
        }
        return $book;
    }
}

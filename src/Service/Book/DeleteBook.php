<?php

namespace App\Service\Book;

use App\Repository\BookRepository;
use Exception;
use Symfony\Component\Uid\Uuid;

class DeleteBook
{

    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $id)
    {
        $book = $this->bookRepository->find(Uuid::fromString($id));
        if (!$book) {
            throw new Exception("Este libro no existe.");
        } else {
            $this->bookRepository->delete($book);
        }
    }
}

<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class BookManager
{

    private $em;
    private $bookRepository;

    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository)
    {
        $this->em = $em;
        $this->bookRepository = $bookRepository;
    }

    public function getRepository(): BookRepository
    {
        return $this->bookRepository;
    }

    public function find(Uuid $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function create(): Book
    {
        $book = new Book();
        return $book;
    }

    public function save(Book $book): Book
    {
        $this->em->persist($book);
        $this->em->flush();
        return $book;
    }
    public function reload(Book $book): Book
    {
        $this->em->refresh($book);
        return $book;
    }

    public function delete(Book $book)
    {
        $this->em->remove($book);
        $this->em->flush();
    }
}

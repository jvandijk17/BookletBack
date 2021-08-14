<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
use App\Service\BookFormProcessor;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as Vista;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function getAction(BookRepository $bookRepository)
    {
        return $bookRepository->findAll();
    }
    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function postAction(
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        $book = Book::create();
        [$book, $error] = ($bookFormProcessor)($book, $request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return Vista::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function editAction(
        String $id,
        BookFormProcessor $bookFormProcessor,
        GetBook $getBook,
        Request $request
    ) {
        $book = ($getBook)($id);
        if (!$book) {
            return Vista::create('El libro solicitado no se ha encontrado', Response::HTTP_BAD_REQUEST);
        }
        [$book, $error] = ($bookFormProcessor)($book, $request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return Vista::create($data, $statusCode);
    }

    /**
     * @Rest\Get(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function getSingleAction(
        String $id,
        GetBook $getBook
    ) {
        $book = ($getBook)($id);
        if (!$book) {
            return Vista::create('El libro solicitado no se ha encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $book;
    }

    /**
     * @Rest\Delete(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function deleteAction(
        String $id,
        DeleteBook $deleteBook
    ) {
        try {
            ($deleteBook)($id);
        } catch (Throwable $tr) {
            return Vista::create('Libro no encontrado', Response::HTTP_BAD_REQUEST);
        }
        return Vista::create('Libro Eliminado', Response::HTTP_NO_CONTENT);
    }
}

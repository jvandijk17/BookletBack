<?php

namespace App\Controller\Api;

use App\Service\BookManager;
use App\Service\BookFormProcessor;
use App\Service\FileUploader;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View as Vista;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function getAction(BookManager $bm)
    {
        return $bm->getRepository()->findAll();
    }
    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function postAction(
        BookManager $bookManager,
        BookFormProcessor $bookFormProcessor,
        Request $request,
        FileUploader $defaultStorage
    ) {
        $book = $bookManager->create();
        [$book, $error] = ($bookFormProcessor)($book, $request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return Vista::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function editAction(
        int $id,
        BookFormProcessor $bookFormProcessor,
        BookManager $bookManager,
        Request $request
    ) {
        $book = $bookManager->find($id);
        if (!$book) {
            return Vista::create('El libro solicitado no se ha encontrado', Response::HTTP_BAD_REQUEST);
        }
        [$book, $error] = ($bookFormProcessor)($book, $request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return Vista::create($data, $statusCode);
    }

    /**
     * @Rest\Get(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function getSingleAction(
        int $id,
        BookManager $bookManager
    ) {
        $book = $bookManager->find($id);
        if (!$book) {
            return Vista::create('El libro solicitado no se ha encontrado', Response::HTTP_BAD_REQUEST);
        }
        return $book;
    }

    /**
     * @Rest\Delete(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function deleteAction(
        int $id,
        BookManager $bookManager,
    ) {
        $book = $bookManager->find($id);
        if (!$book) {
            return Vista::create('El libro solicitado no se ha encontrado', Response::HTTP_BAD_REQUEST);
        }
        $bookManager->delete($book);
        return Vista::create('Libro Eliminado', Response::HTTP_NO_CONTENT);
    }
}

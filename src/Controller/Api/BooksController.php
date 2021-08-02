<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use League\Flysystem\FilesystemOperator;

class BooksController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function getActions(BookRepository $br)
    {
        return $br->findAll();
    }
    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function postAction(EntityManagerInterface $em, Request $request, FilesystemOperator $defaultStorage)
    {
        $bookDto = new BookDto();
        $form = $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $extension = explode('/', mime_content_type($bookDto->base64Image))[1];
            $data = explode(',', $bookDto->base64Image);
            $path = sprintf('/books/%s.%s', uniqid('book_', true), $extension);        
            $defaultStorage->write($path, base64_decode($data[1]));
            $book = new Book();
            $book->setTitle($bookDto->title);
            $book->setImage($path);
            $em->persist($book);
            $em->flush();
            return $book;
        }
        return $form;
    }
}

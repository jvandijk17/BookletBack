<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/books", name="books_get")
     *      
     */
    public function list(BookRepository $br)
    {
        $books = $br->findAll();
        $booksAsArray = [];
        foreach($books as $book) {
            $booksAsArray[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'image' => $book->getImage()
            ];
        }
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => $booksAsArray
        ]);
        return $response;
    }

    /**
     * @Route("/book", name="create_book")
     *      
     */
    public function createBook(Request $request, EntityManagerInterface $em)
    {
        $book = new Book();
        $book->setTitle($request->get('title'));
        $em->persist($book);
        $em->flush();
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            [
                'id' => $book->getId(),
                'title' => $book->getTitle()
            ]
        ]);
        return $response;
    }
}

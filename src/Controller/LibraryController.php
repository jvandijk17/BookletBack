<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/library/list", name="library_list")
     *      
     */
    public function list()
    {
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => [
                [
                    'id' => 1,
                    'title' => 'Hacia rutas salvajes'
                ],
                [
                    'id' => 2,
                    'title' => 'El nombre del viento'
                ]
            ]
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
        $book->setTitle('Hacia rutas salvajes');
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

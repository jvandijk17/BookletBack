<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\FileUploader;
use App\Service\Category\GetCategory;
use App\Service\Category\CreateCategory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class BookFormProcessor
{
    private GetBook $getBook;
    private BookRepository $bookRepository;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;
    private CreateCategory $createCategory;
    private GetCategory $getCategory;

    public function __construct(
        GetBook $getBook,
        BookRepository $bookRepository,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory,
        GetCategory $getCategory,
        CreateCategory $createCategory
    ) {
        $this->getBook = $getBook;
        $this->bookRepository = $bookRepository;
        $this->createCategory = $createCategory;
        $this->getCategory = $getCategory;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $book = null;
        $bookDto = null;

        if ($bookId === null) {
            $book = Book::create();
            $bookDto = BookDto::createEmpty();
        } else {
            $book = ($this->getBook)($bookId);
            $bookDto = BookDto::createFromBook($book);
            foreach ($book->getCategories() as $category) {
                $bookDto->categories[] = CategoryDto::createFromCategory($category);
            }
        }

        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'El formulario no se ha enviado.'];
        }

        if (!$form->isValid()) {
            return [null, $form];
        }

        $categories = [];
        foreach ($bookDto->getCategories() as $newCategoryDto) {
            $category = null;
            if ($newCategoryDto->getId() !== null) {
                $category = ($this->getCategory)($newCategoryDto->getId());
            }
            if ($category === null) {
                $category = ($this->createCategory)($newCategoryDto->getName());
            }
            $categories[] = $category;
        }

        $filename = null;
        if ($bookDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }
        $book->update($bookDto->title, $filename, ...$categories);
        $this->bookRepository->save($book);
        return [$book, null];
    }
}

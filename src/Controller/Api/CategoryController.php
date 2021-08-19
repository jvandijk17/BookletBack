<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Service\Category\CategoryFormProcessor;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View as Vista;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function getAction(CategoryRepository $cr)
    {
        return $cr->findAll();
    }

    /**
     * @Rest\post(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     * 
     */
    public function postAction(
        Request $request,
        CategoryFormProcessor $categoryFormProcessor
    ) {
        [$category, $error] = ($categoryFormProcessor)($request);
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;
        return Vista::create($data, $statusCode);
    }
}

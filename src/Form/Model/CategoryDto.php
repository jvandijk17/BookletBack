<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Category;

class CategoryDto
{
    public $id;
    /**
     * @Assert\NotBlank(
     * message = "El Nombre de la Categoría no puede estar en blanco."
     * )
     */
    public $name;

    public static function createFromCategory(Category $category): self
    {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();
        return $dto;
    }   
}

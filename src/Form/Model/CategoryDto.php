<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Category;
use Symfony\Component\Uid\Uuid;

class CategoryDto
{
    public ?Uuid $id = null;
    /**
     * @Assert\NotBlank(
     * message = "El Nombre de la Categoría no puede estar en blanco."
     * )
     */
    public ?string $name = null;

    public static function createFromCategory(Category $category): self
    {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();
        return $dto;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

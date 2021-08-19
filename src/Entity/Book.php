<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id     
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)          
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books")
     */
    private $categories;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->categories = new ArrayCollection();
    }

    public static function create(): self
    {
        return new self();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function updateCategories(Category ...$categories)
    {
        /** @var Category[]|ArrayCollection */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // Eliminar Categorías
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategories, $categories)) {
                $this->removeCategory($originalCategory);
            }
        }

        // Añadir Categorías
        foreach ($categories as $newCategory) {
            if (!$originalCategories->contains(!$newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    public function update(string $title, ?string $image, Category ...$categories)
    {
        $this->title = $title;
        $this->image = $image;
        $this->updateCategories(...$categories);
    }
}

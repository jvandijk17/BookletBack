<?php

namespace App\Form\Model;
use Symfony\Component\Validator\Constraints as Assert;

class BookDto {   

    /**
     * @Assert\NotBlank(
     * message = "El Título no puede estar en blanco."
     * )
     */
    public $title;
    public $base64Image;
}
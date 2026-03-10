<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LibroRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Representa un libro dentro de la biblioteca.
 *
 * Contiene la información básica del libro: título, autor e ISBN.
 * Esta entidad puede relacionarse con préstamos realizados por los usuarios.
 */
#[ORM\Entity(repositoryClass: LibroRepository::class)]
#[ApiResource]
class Libro
{
    /**
     * Identificador único del libro.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Título del libro.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $titulo = null;

    /**
     * Autor del libro.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $autor = null;

    /**
     * Código ISBN del libro.
     * Es opcional pero debe ser único si se proporciona.
     */
    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $isbn = null;

    /**
     * Devuelve el identificador del libro.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Devuelve el título del libro.
     */
    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    /**
     * Establece el título del libro.
     */
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * Devuelve el autor del libro.
     */
    public function getAutor(): ?string
    {
        return $this->autor;
    }

    /**
     * Establece el autor del libro.
     */
    public function setAutor(string $autor): self
    {
        $this->autor = $autor;
        return $this;
    }

    /**
     * Devuelve el ISBN del libro.
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * Establece el ISBN del libro.
     */
    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;
        return $this;
    }
}
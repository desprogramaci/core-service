<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Processor\PrestamoProcessor;
use App\Repository\PrestamoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Representa un préstamo realizado por un usuario.
 *
 * Cada préstamo relaciona a un usuario con un libro en una fecha concreta.
 * La creación del préstamo pasa por un procesador que aplica reglas de negocio,
 * como el límite de 3 préstamos activos por usuario.
 */
#[ORM\Entity(repositoryClass: PrestamoRepository::class)]
#[ApiResource(
    operations: [
        new Post(processor: PrestamoProcessor::class),
    ]
)]
class Prestamo
{
    /**
     * Identificador único del préstamo.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Usuario que realiza el préstamo.
     * La relación es obligatoria.
     */
    #[ORM\ManyToOne(inversedBy: 'prestamos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Usuario $usuario = null;

    /**
     * Libro que se presta al usuario.
     * La relación es obligatoria.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Libro $libro = null;

    /**
     * Fecha en la que se realizó el préstamo.
     * Debe ser una fecha válida.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $fechaPrestamo = null;

    /**
     * Devuelve el identificador del préstamo.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Devuelve el usuario asociado al préstamo.
     */
    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    /**
     * Establece el usuario que realiza el préstamo.
     */
    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * Devuelve el libro prestado.
     */
    public function getLibro(): ?Libro
    {
        return $this->libro;
    }

    /**
     * Establece el libro que se presta.
     */
    public function setLibro(?Libro $libro): self
    {
        $this->libro = $libro;
        return $this;
    }

    /**
     * Devuelve la fecha del préstamo.
     */
    public function getFechaPrestamo(): ?\DateTimeInterface
    {
        return $this->fechaPrestamo;
    }

    /**
     * Establece la fecha del préstamo.
     */
    public function setFechaPrestamo(\DateTimeInterface $fechaPrestamo): self
    {
        $this->fechaPrestamo = $fechaPrestamo;
        return $this;
    }
}
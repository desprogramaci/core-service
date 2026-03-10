<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Representa a un usuario de la biblioteca.
 *
 * Contiene los datos personales del usuario y su relación con los préstamos
 * que ha realizado. Cada usuario puede tener varios préstamos asociados.
 */
#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ApiResource]
class Usuario
{
    /**
     * Identificador único del usuario.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nombre del usuario.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $nombre = null;

    /**
     * Apellidos del usuario.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $apellidos = null;

    /**
     * DNI del usuario.
     * Debe ser único en el sistema.
     */
    #[ORM\Column(length: 15, unique: true)]
    #[Assert\NotBlank]
    private ?string $dni = null;

    /**
     * Colección de préstamos asociados al usuario.
     *
     * @var Collection<int, Prestamo>
     */
    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Prestamo::class)]
    private Collection $prestamos;

    public function __construct()
    {
        $this->prestamos = new ArrayCollection();
    }

    /**
     * Devuelve el identificador del usuario.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Devuelve el nombre del usuario.
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre del usuario.
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Devuelve los apellidos del usuario.
     */
    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    /**
     * Establece los apellidos del usuario.
     */
    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;
        return $this;
    }

    /**
     * Devuelve el DNI del usuario.
     */
    public function getDni(): ?string
    {
        return $this->dni;
    }

    /**
     * Establece el DNI del usuario.
     */
    public function setDni(string $dni): self
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * Devuelve la colección de préstamos del usuario.
     *
     * @return Collection<int, Prestamo>
     */
    public function getPrestamos(): Collection
    {
        return $this->prestamos;
    }

    /**
     * Añade un préstamo a la colección del usuario.
     */
    public function addPrestamo(Prestamo $prestamo): self
    {
        if (!$this->prestamos->contains($prestamo)) {
            $this->prestamos->add($prestamo);
            $prestamo->setUsuario($this);
        }
        return $this;
    }

    /**
     * Elimina un préstamo de la colección del usuario.
     */
    public function removePrestamo(Prestamo $prestamo): self
    {
        if ($this->prestamos->removeElement($prestamo)) {
            if ($prestamo->getUsuario() === $this) {
                $prestamo->setUsuario(null);
            }
        }
        return $this;
    }
}
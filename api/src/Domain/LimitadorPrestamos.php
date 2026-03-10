<?php

namespace App\Domain;

use App\Entity\Prestamo;
use App\Entity\Usuario;
use App\Entity\Libro;
use App\Repository\PrestamoRepository;

/**
 * Servicio de dominio encargado de validar las reglas de negocio
 * relacionadas con los préstamos de la biblioteca.
 *
 * Verifica dos condiciones:
 *  - Que el usuario no supere el límite de 3 préstamos activos.
 *  - Que el libro no esté actualmente prestado.
 *
 * Este servicio encapsula la lógica del dominio y permite mantener
 * el código más limpio y separado de la capa de infraestructura.
 */
class LimitadorPrestamos
{
    /**
     * Constructor del servicio.
     *
     * @param PrestamoRepository $repo Repositorio para consultar préstamos existentes.
     */
    public function __construct(private PrestamoRepository $repo) {}

    /**
     * Ejecuta todas las validaciones necesarias antes de registrar un préstamo.
     *
     * @param Prestamo $prestamo El préstamo que se desea validar.
     *
     * @throws \DomainException Si alguna regla de negocio no se cumple.
     */
    public function verificar(Prestamo $prestamo): void
    {
        $this->validarLimite($prestamo->getUsuario());
        $this->validarLibroDisponible($prestamo->getLibro());
    }

    /**
     * Valida que el usuario no tenga más de 3 préstamos activos.
     *
     * @param Usuario $usuario Usuario que solicita el préstamo.
     *
     * @throws \DomainException Si el usuario ya alcanzó el límite.
     */
    public function validarLimite(Usuario $usuario): void
    {
        $total = $this->repo->count([
            'usuario' => $usuario,
            'fechaDevolucion' => null
        ]);

        if ($total >= 3) {
            throw new \DomainException("El usuario ya tiene 3 préstamos activos.");
        }
    }

    /**
     * Valida que el libro no esté prestado actualmente.
     *
     * @param Libro $libro Libro que se desea prestar.
     *
     * @throws \DomainException Si el libro ya está prestado.
     */
    public function validarLibroDisponible(Libro $libro): void
    {
        $prestado = $this->repo->count([
            'libro' => $libro,
            'fechaDevolucion' => null
        ]);

        if ($prestado > 0) {
            throw new \DomainException("El libro ya está prestado.");
        }
    }
}
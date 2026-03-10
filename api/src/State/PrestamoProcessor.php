<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\LimitadorPrestamos;
use App\Entity\Prestamo;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Procesador encargado de gestionar la creación de préstamos desde la API.
 *
 * Este procesador actúa antes de que el préstamo sea persistido, aplicando
 * las reglas de negocio definidas en el dominio (LimitadorPrestamos) y
 * guardando finalmente el préstamo en la base de datos.
 *
 * Forma parte de la capa State de API Platform, permitiendo separar la lógica
 * de negocio de la infraestructura y mantener un flujo claro en la creación
 * de recursos.
 */
class PrestamoProcessor implements ProcessorInterface
{
    /**
     * Constructor del procesador.
     *
     * @param EntityManagerInterface $em Maneja la persistencia de entidades.
     * @param LimitadorPrestamos $limitador Servicio de dominio que valida reglas de negocio.
     */
    public function __construct(
        private EntityManagerInterface $em,
        private LimitadorPrestamos $limitador
    ) {}

    /**
     * Procesa el préstamo antes de ser guardado.
     *
     * @param mixed $data Datos recibidos por la API (debe ser un Prestamo).
     * @param Operation $operation Operación de API Platform que se está ejecutando.
     * @param array $uriVariables Variables de la ruta.
     * @param array $context Contexto adicional.
     *
     * @return mixed El préstamo procesado o los datos sin modificar.
     *
     * @throws \DomainException Si alguna regla de negocio no se cumple.
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        // Si no es un préstamo, no hacemos nada.
        if (!$data instanceof Prestamo) {
            return $data;
        }

        // Validaciones del dominio (límite de préstamos y disponibilidad del libro).
        $this->limitador->verificar($data);

        // Persistencia del préstamo.
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}
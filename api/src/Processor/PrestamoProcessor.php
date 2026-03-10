<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Prestamo;
use App\Repository\PrestamoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Procesa la creación de un préstamo aplicando reglas de negocio.
 *
 * Este procesador se ejecuta cuando se crea un préstamo desde la API.
 * Valida que el usuario no supere el límite de 3 préstamos activos
 * y asigna la fecha del préstamo si no se ha enviado.
 */
class PrestamoProcessor implements ProcessorInterface
{
    /**
     * Constructor del procesador.
     *
     * @param EntityManagerInterface $em Maneja la persistencia de datos.
     * @param PrestamoRepository $prestamoRepository Permite consultar préstamos existentes.
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PrestamoRepository $prestamoRepository
    ) {}

    /**
     * Ejecuta el procesamiento del préstamo antes de guardarlo.
     *
     * @param mixed $data Datos recibidos por la API (debe ser un Prestamo).
     * @param Operation $operation Operación de API Platform.
     * @param array $uriVariables Variables de la ruta.
     * @param array $context Contexto adicional.
     *
     * @return mixed El préstamo procesado o los datos sin modificar.
     *
     * @throws BadRequestHttpException Si el usuario no existe o supera el límite de préstamos.
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Si no es un préstamo, no hacemos nada.
        if (!$data instanceof Prestamo) {
            return $data;
        }

        // Validación: el préstamo debe tener un usuario.
        $usuario = $data->getUsuario();
        if (!$usuario) {
            throw new BadRequestHttpException('El usuario es obligatorio.');
        }

        // Regla de negocio: máximo 3 préstamos por usuario.
        $totalPrestamos = $this->prestamoRepository->count(['usuario' => $usuario]);
        if ($totalPrestamos >= 3) {
            throw new BadRequestHttpException('El usuario ya tiene 3 préstamos.');
        }

        // Si no se envía fecha, se asigna la actual.
        if (null === $data->getFechaPrestamo()) {
            $data->setFechaPrestamo(new \DateTime('now'));
        }

        // Guardamos el préstamo.
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }
}
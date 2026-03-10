<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\PrestamosPorUsuario;
use App\Entity\Prestamo;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Proveedor encargado de generar estadísticas de préstamos por usuario.
 *
 * Este provider se ejecuta cuando se consulta el endpoint de estadísticas
 * definido en ApiResource. Recibe los parámetros "desde" y "hasta" desde
 * los filtros de API Platform, valida las fechas y construye una consulta
 * agregada para devolver el total de préstamos por usuario en ese rango.
 *
 * Devuelve una colección de objetos PrestamosPorUsuario.
 */
class PrestamosPorUsuarioProvider implements ProviderInterface
{
    /**
     * Constructor del provider.
     *
     * @param EntityManagerInterface $em Permite construir y ejecutar consultas DQL.
     */
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Obtiene las estadísticas de préstamos agrupadas por usuario.
     *
     * @param Operation $operation Operación de API Platform.
     * @param array $uriVariables Variables de la ruta.
     * @param array $context Contexto adicional, incluyendo filtros.
     *
     * @return array Lista de objetos PrestamosPorUsuario.
     *
     * @throws BadRequestHttpException Si faltan parámetros o las fechas son inválidas.
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $filters = $context['filters'] ?? [];

        $desdeParam = $filters['desde'] ?? null;
        $hastaParam = $filters['hasta'] ?? null;

        // Validación de parámetros obligatorios
        if (!$desdeParam || !$hastaParam) {
            throw new BadRequestHttpException("Los parámetros 'desde' y 'hasta' son obligatorios.");
        }

        // Validación de formato de fecha
        try {
            $desde = new \DateTimeImmutable($desdeParam);
            $hasta = new \DateTimeImmutable($hastaParam);
        } catch (\Exception $e) {
            throw new BadRequestHttpException("Formato de fecha inválido. Usa YYYY-MM-DD.");
        }

        // Validación de rango lógico
        if ($desde > $hasta) {
            throw new BadRequestHttpException("El parámetro 'desde' debe ser anterior a 'hasta'.");
        }

        // Construcción de la consulta agregada
        $qb = $this->em->createQueryBuilder()
            ->select('u.id AS usuarioId, COUNT(p.id) AS totalPrestamos')
            ->from(Prestamo::class, 'p')
            ->join('p.usuario', 'u')
            ->where('p.fechaPrestamo BETWEEN :desde AND :hasta')
            ->groupBy('u.id')
            ->setParameter('desde', $desde)
            ->setParameter('hasta', $hasta);

        $resultados = $qb->getQuery()->getResult();

        // Transformación a DTOs
        return array_map(
            fn($r) => new PrestamosPorUsuario(
                usuarioId: (int) $r['usuarioId'],
                totalPrestamos: (int) $r['totalPrestamos']
            ),
            $resultados
        );
    }
}
<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Representa el resultado de las estadísticas de préstamos por usuario.
 *
 * Este DTO se utiliza para devolver información agregada en endpoints
 * que muestran cuántos préstamos ha realizado un usuario en un periodo
 * determinado. No es una entidad de base de datos, solo un objeto de
 * transferencia de datos para respuestas de la API.
 */
class PrestamosPorUsuario
{
    /**
     * Identificador del usuario al que pertenecen los préstamos.
     */
    #[Groups(['estadisticas:read'])]
    public int $usuarioId;

    /**
     * Número total de préstamos realizados por el usuario.
     */
    #[Groups(['estadisticas:read'])]
    public int $totalPrestamos;

    /**
     * Constructor del DTO.
     *
     * @param int $usuarioId Identificador del usuario.
     * @param int $totalPrestamos Total de préstamos asociados.
     */
    public function __construct(int $usuarioId, int $totalPrestamos)
    {
        $this->usuarioId = $usuarioId;
        $this->totalPrestamos = $totalPrestamos;
    }
}
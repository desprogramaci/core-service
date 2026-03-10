<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\PrestamosPorUsuarioProvider;
use App\Dto\PrestamosPorUsuario;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Recurso de API que representa las estadísticas de préstamos por usuario.
 *
 * Este recurso no corresponde a una entidad de base de datos. Se utiliza
 * exclusivamente para exponer datos agregados mediante un endpoint de solo lectura.
 *
 * La operación definida devuelve una colección de objetos con:
 *  - usuarioId: identificador del usuario
 *  - totalPrestamos: número total de préstamos realizados en el rango solicitado
 *
 * La lógica para obtener estos datos se encuentra en el provider
 * PrestamosPorUsuarioProvider.
 */
#[ApiResource(
    shortName: 'EstadisticasPrestamos',
    operations: [
        new GetCollection(
            uriTemplate: '/estadisticas/prestamos',
            provider: PrestamosPorUsuarioProvider::class,
            output: PrestamosPorUsuario::class,
            normalizationContext: ['groups' => ['estadisticas:read']]
        )
    ]
)]
class EstadisticasPrestamos
{
    /**
     * Identificador del usuario al que pertenecen las estadísticas.
     */
    #[Groups(['estadisticas:read'])]
    public int $usuarioId;

    /**
     * Total de préstamos realizados por el usuario.
     */
    #[Groups(['estadisticas:read'])]
    public int $totalPrestamos;

    /**
     * Constructor del recurso.
     *
     * @param int $usuarioId Identificador del usuario.
     * @param int $totalPrestamos Número total de préstamos asociados.
     */
    public function __construct(int $usuarioId, int $totalPrestamos)
    {
        $this->usuarioId = $usuarioId;
        $this->totalPrestamos = $totalPrestamos;
    }
}
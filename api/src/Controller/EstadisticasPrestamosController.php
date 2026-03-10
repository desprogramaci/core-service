<?php

namespace App\Controller;

use App\Repository\PrestamoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controlador encargado de devolver estadísticas de préstamos por usuario.
 *
 * Este controlador se utiliza como acción invocable (__invoke) dentro de
 * API Platform. Recibe dos parámetros de fecha por query string:
 *
 *   - from: fecha de inicio del rango
 *   - to:   fecha de fin del rango
 *
 * Con esas fechas, consulta el repositorio de préstamos para obtener
 * estadísticas agregadas por usuario y devuelve la información en formato JSON.
 */
class EstadisticasPrestamosController
{
    /**
     * Constructor del controlador.
     *
     * @param PrestamoRepository $repo Repositorio para consultar estadísticas de préstamos.
     */
    public function __construct(private PrestamoRepository $repo) {}

    /**
     * Acción invocable que procesa la petición y devuelve las estadísticas.
     *
     * @param Request $request Petición HTTP con los parámetros 'from' y 'to'.
     *
     * @return JsonResponse Respuesta con las estadísticas en formato JSON.
     *
     * @throws \Exception Si las fechas proporcionadas no son válidas.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $from = new \DateTimeImmutable($request->query->get('from'));
        $to   = new \DateTimeImmutable($request->query->get('to'));

        return new JsonResponse(
            $this->repo->estadisticasPorUsuario($from, $to)
        );
    }
}
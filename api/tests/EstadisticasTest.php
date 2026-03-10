<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

/**
 * Pruebas funcionales del endpoint de estadísticas de préstamos.
 *
 * Este test verifica el correcto funcionamiento del recurso expuesto en
 * `/estadisticas/prestamos`, el cual devuelve información agregada sobre
 * la cantidad total de préstamos realizados por los usuarios dentro de un
 * rango de fechas determinado.
 *
 * El objetivo principal es asegurar que:
 *  - El endpoint responde correctamente (HTTP 200).
 *  - La estructura de la respuesta sigue el formato Hydra utilizado por API Platform.
 *  - La colección devuelta contiene elementos.
 *  - Cada elemento incluye el campo `totalPrestamos`, que representa el total
 *    de préstamos realizados por un usuario en el periodo solicitado.
 */
class EstadisticasTest extends ApiTestCase
{
    /**
     * Verifica que el endpoint de estadísticas de préstamos devuelve datos válidos.
     *
     * Se envía una petición GET con los parámetros `desde` y `hasta`, los cuales
     * son utilizados por el provider para generar estadísticas agregadas.
     *
     * El test confirma:
     *  - Que la respuesta es exitosa.
     *  - Que existe la clave `hydra:member`.
     *  - Que la colección no está vacía.
     *  - Que cada elemento contiene el campo `totalPrestamos`.
     *
     * @return void
     */
    public function testEstadisticasPrestamos(): void
    {
        $client = static::createClient();

        $response = $client->request('GET', '/estadisticas/prestamos', [
            'query' => [
                'desde' => '2024-01-01',
                'hasta' => '2024-12-31'
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $data = $response->toArray();

        $this->assertArrayHasKey('hydra:member', $data);
        $this->assertNotEmpty($data['hydra:member']);
        $this->assertArrayHasKey('totalPrestamos', $data['hydra:member'][0]);
    }
}
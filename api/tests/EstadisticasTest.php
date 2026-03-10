<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class EstadisticasTest extends ApiTestCase
{
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
<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class LibroTest extends ApiTestCase
{
    public function testCrearLibro(): void
    {
        $client = static::createClient();

        $response = $client->request('POST', '/libros', [
            'json' => [
                'titulo' => 'El Quijote',
                'autor' => 'Cervantes',
                'isbn' => '1234567890'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['titulo' => 'El Quijote']);
    }

    public function testListarLibros(): void
    {
        static::createClient()->request('GET', '/libros');
        $this->assertResponseIsSuccessful();
    }
}
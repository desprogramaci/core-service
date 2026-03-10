<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class PrestamoTest extends ApiTestCase
{
    public function testCrearPrestamo(): void
    {
        $client = static::createClient();

        $usuario = $client->request('POST', '/usuarios', [
            'json' => [
                'nombre' => 'Juan',
                'apellidos' => 'Pérez',
                'dni' => substr('T' . uniqid(), 0, 15)
            ]
        ])->toArray();

        $this->assertResponseStatusCodeSame(201);

        $libro = $client->request('POST', '/libros', [
            'json' => [
                'titulo' => 'Libro A',
                'autor' => 'Autor A'
            ]
        ])->toArray();

        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', '/prestamos', [
            'json' => [
                'usuario' => '/usuarios/' . $usuario['id'],
                'libro' => '/libros/' . $libro['id'],
                'fechaPrestamo' => '2024-05-10'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testMaximoTresPrestamos(): void
    {
        $client = static::createClient();

        $usuario = $client->request('POST', '/usuarios', [
            'json' => [
                'nombre' => 'Ana',
                'apellidos' => 'López',
                'dni' => substr('T' . uniqid(), 0, 15)
            ]
        ])->toArray();

        $this->assertResponseStatusCodeSame(201);

        $libro = $client->request('POST', '/libros', [
            'json' => [
                'titulo' => 'Libro B',
                'autor' => 'Autor B'
            ]
        ])->toArray();

        $this->assertResponseStatusCodeSame(201);

        for ($i = 0; $i < 3; $i++) {
            $client->request('POST', '/prestamos', [
                'json' => [
                    'usuario' => '/usuarios/' . $usuario['id'],
                    'libro' => '/libros/' . $libro['id'],
                    'fechaPrestamo' => '2024-05-10'
                ]
            ]);
            $this->assertResponseStatusCodeSame(201);
        }

        $client->request('POST', '/prestamos', [
            'json' => [
                'usuario' => '/usuarios/' . $usuario['id'],
                'libro' => '/libros/' . $libro['id'],
                'fechaPrestamo' => '2024-05-10'
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
}

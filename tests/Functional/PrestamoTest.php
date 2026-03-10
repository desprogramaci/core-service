<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PrestamoTest extends WebTestCase
{
    public function testCrearPrestamo(): void
    {
        $client = static::createClient();

        // Crear usuario
        $client->request(
            'POST',
            '/usuarios',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nombre' => 'Juan',
                'apellidos' => 'Pérez',
                'dni' => '12345678A'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $usuario = json_decode($client->getResponse()->getContent(), true);

        // Crear libro
        $client->request(
            'POST',
            '/libros',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'titulo' => 'Libro A',
                'autor' => 'Autor A'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $libro = json_decode($client->getResponse()->getContent(), true);

        // Crear préstamo
        $client->request(
            'POST',
            '/prestamos',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'usuario' => '/usuarios/' . $usuario['id'],
                'libro' => '/libros/' . $libro['id'],
                'fechaPrestamo' => '2024-05-10'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testMaximoTresPrestamos(): void
    {
        $client = static::createClient();

        // Crear usuario
        $client->request(
            'POST',
            '/usuarios',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'nombre' => 'Ana',
                'apellidos' => 'López',
                'dni' => '99999999Z'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $usuario = json_decode($client->getResponse()->getContent(), true);

        // Crear libro
        $client->request(
            'POST',
            '/libros',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'titulo' => 'Libro B',
                'autor' => 'Autor B'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $libro = json_decode($client->getResponse()->getContent(), true);

        // Crear 3 préstamos válidos
        for ($i = 0; $i < 3; $i++) {
            $client->request(
                'POST',
                '/prestamos',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                json_encode([
                    'usuario' => '/usuarios/' . $usuario['id'],
                    'libro' => '/libros/' . $libro['id'],
                    'fechaPrestamo' => '2024-05-10'
                ])
            );
            $this->assertResponseStatusCodeSame(201);
        }

        // Intentar el 4º → debe fallar
        $client->request(
            'POST',
            '/prestamos',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'usuario' => '/usuarios/' . $usuario['id'],
                'libro' => '/libros/' . $libro['id'],
                'fechaPrestamo' => '2024-05-10'
            ])
        );

        $this->assertResponseStatusCodeSame(400);
    }
}
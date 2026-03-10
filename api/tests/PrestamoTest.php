<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

/**
 * Pruebas funcionales del recurso Prestamo.
 *
 * Este conjunto de tests valida el comportamiento del sistema respecto a la
 * creación de préstamos y la aplicación de las reglas de negocio asociadas.
 *
 * Los objetivos principales de estas pruebas son:
 *  - Verificar que un préstamo puede crearse correctamente cuando se proporcionan
 *    un usuario, un libro y una fecha válidos.
 *  - Confirmar que la regla de negocio que limita a un máximo de tres préstamos
 *    activos por usuario se aplica correctamente, devolviendo un error al intentar
 *    superar dicho límite.
 */
class PrestamoTest extends ApiTestCase
{
    /**
     * Verifica la creación correcta de un préstamo.
     *
     * Este test realiza los siguientes pasos:
     *  1. Crea un usuario válido.
     *  2. Crea un libro válido.
     *  3. Envía una petición POST al endpoint /prestamos con los datos necesarios.
     *
     * El test confirma:
     *  - Que cada creación de usuario y libro devuelve un código HTTP 201.
     *  - Que el préstamo también se crea correctamente con código HTTP 201.
     *
     * @return void
     */
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

    /**
     * Verifica la regla de negocio que limita a un máximo de tres préstamos por usuario.
     *
     * Este test comprueba que:
     *  - Un usuario puede realizar hasta tres préstamos válidos.
     *  - Al intentar realizar un cuarto préstamo, el sistema debe rechazar la operación
     *    devolviendo un código HTTP 400.
     *
     * Flujo del test:
     *  1. Se crea un usuario válido.
     *  2. Se crea un libro válido.
     *  3. Se realizan tres préstamos válidos, cada uno con respuesta 201.
     *  4. Se intenta realizar un cuarto préstamo, que debe ser rechazado.
     *
     * Esta prueba garantiza que la lógica de negocio que controla el límite de préstamos
     * activos por usuario funciona correctamente.
     *
     * @return void
     */
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
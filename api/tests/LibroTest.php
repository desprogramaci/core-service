<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

/**
 * Pruebas funcionales del recurso Libro.
 *
 * Este conjunto de tests verifica el correcto funcionamiento de los endpoints
 * relacionados con la gestión de libros dentro de la API. Se comprueba tanto
 * la creación de un nuevo libro como la obtención de la lista completa de libros.
 *
 * Los objetivos principales de estas pruebas son:
 *  - Validar que la creación de libros funciona correctamente y devuelve un código 201.
 *  - Confirmar que los datos enviados en la petición se reflejan en la respuesta.
 *  - Verificar que el endpoint de listado responde correctamente y está accesible.
 */
class LibroTest extends ApiTestCase
{
    /**
     * Verifica la creación de un libro mediante el endpoint POST /libros.
     *
     * Se envía un objeto JSON con los campos obligatorios del recurso Libro.
     * El test confirma:
     *  - Que la respuesta devuelve un código HTTP 201 (creado).
     *  - Que el contenido de la respuesta incluye el título enviado.
     *
     * @return void
     */
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

    /**
     * Verifica que el endpoint GET /libros devuelve una respuesta exitosa.
     *
     * Este test asegura que el listado de libros está disponible y que
     * la API responde correctamente al solicitar la colección completa.
     *
     * @return void
     */
    public function testListarLibros(): void
    {
        static::createClient()->request('GET', '/libros');
        $this->assertResponseIsSuccessful();
    }
}
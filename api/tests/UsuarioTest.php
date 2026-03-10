<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

/**
 * Pruebas funcionales del recurso Usuario.
 *
 * Este conjunto de tests valida el correcto funcionamiento de los endpoints
 * relacionados con la gestión de usuarios dentro de la API. Se comprueba tanto
 * la creación de un usuario como la obtención del listado completo.
 *
 * Los objetivos principales de estas pruebas son:
 *  - Verificar que un usuario puede crearse correctamente mediante el endpoint POST.
 *  - Confirmar que los datos enviados en la petición se reflejan en la respuesta.
 *  - Asegurar que el endpoint de listado de usuarios responde correctamente.
 */
class UsuarioTest extends ApiTestCase
{
    /**
     * Verifica la creación correcta de un usuario.
     *
     * Este test realiza una petición POST al endpoint /usuarios enviando los
     * datos mínimos necesarios para crear un usuario válido. Se genera un DNI
     * único para evitar conflictos con restricciones de unicidad.
     *
     * El test confirma:
     *  - Que la respuesta devuelve un código HTTP 201 (creado).
     *  - Que el DNI enviado coincide con el devuelto en la respuesta.
     *
     * @return void
     */
    public function testCrearUsuario(): void
    {
        $client = static::createClient();
        $dni = substr('T' . uniqid(), 0, 15);

        $response = $client->request('POST', '/usuarios', [
            'json' => [
                'nombre' => 'Juan',
                'apellidos' => 'Pérez',
                'dni' => $dni
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['dni' => $dni]);
    }

    /**
     * Verifica que el endpoint GET /usuarios devuelve una respuesta exitosa.
     *
     * Este test asegura que el listado de usuarios está disponible y que
     * la API responde correctamente al solicitar la colección completa.
     *
     * @return void
     */
    public function testListarUsuarios(): void
    {
        static::createClient()->request('GET', '/usuarios');
        $this->assertResponseIsSuccessful();
    }
}
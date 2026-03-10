<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UsuarioTest extends ApiTestCase
{
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

    public function testListarUsuarios(): void
    {
        static::createClient()->request('GET', '/usuarios');
        $this->assertResponseIsSuccessful();
    }
}

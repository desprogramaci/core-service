<?php

use PHPUnit\Framework\TestCase;

/**
 * Prueba básica de verificación del entorno de test.
 *
 * Este test actúa como una comprobación mínima para asegurar que el
 * sistema de pruebas PHPUnit está funcionando correctamente. No valida
 * ninguna funcionalidad de la aplicación, sino que confirma que el
 * framework de testing puede ejecutarse sin errores.
 *
 * Su propósito es servir como "smoke test", es decir, una prueba muy
 * simple que permite detectar rápidamente fallos graves en la
 * configuración del entorno antes de ejecutar el resto de la suite.
 */
class SmokeTest extends TestCase
{
    /**
     * Verifica que PHPUnit puede ejecutar un test básico.
     *
     * Esta prueba consiste únicamente en una afirmación verdadera,
     * lo que permite confirmar que el entorno de pruebas está operativo.
     *
     * @return void
     */
    public function test_basic()
    {
        $this->assertTrue(true);
    }
}

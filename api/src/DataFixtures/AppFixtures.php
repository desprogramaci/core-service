<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use App\Entity\Libro;
use App\Entity\Prestamo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Carga datos iniciales para facilitar las pruebas de la API.
 *
 * Esta clase crea usuarios, libros y algunos préstamos válidos,
 * permitiendo probar los endpoints sin necesidad de insertar datos manualmente.
 *
 * Se ejecuta con:
 *   php bin/console doctrine:fixtures:load
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Usuarios de ejemplo ---
        $usuarios = [];
        $datosUsuarios = [
            ['Juan', 'Pérez', '11111111A'],
            ['Ana', 'García', '22222222B'],
            ['Luis', 'Martínez', '33333333C'],
            ['María', 'López', '44444444D'],
            ['Carlos', 'Sánchez', '55555555E'],
        ];

        foreach ($datosUsuarios as [$nombre, $apellidos, $dni]) {
            $u = new Usuario();
            $u->setNombre($nombre);
            $u->setApellidos($apellidos);
            $u->setDni($dni);
            $manager->persist($u);
            $usuarios[] = $u;
        }

        // --- Libros de ejemplo ---
        $libros = [];
        $datosLibros = [
            ['1984', 'George Orwell', 'ISBN-1984'],
            ['Dune', 'Frank Herbert', 'ISBN-DUNE'],
            ['El Quijote', 'Miguel de Cervantes', 'ISBN-QUIJOTE'],
            ['Fundación', 'Isaac Asimov', 'ISBN-FUNDACION'],
            ['Crimen y Castigo', 'Fiódor Dostoyevski', 'ISBN-CRIMEN'],
        ];

        foreach ($datosLibros as [$titulo, $autor, $isbn]) {
            $l = new Libro();
            $l->setTitulo($titulo);
            $l->setAutor($autor);
            $l->setIsbn($isbn);
            $manager->persist($l);
            $libros[] = $l;
        }

        // --- Préstamos de ejemplo ---
        // Solo 3 para no superar el límite de 3 por usuario
        $prestamos = [
            [$usuarios[0], $libros[0], new \DateTimeImmutable('-10 days')],
            [$usuarios[1], $libros[1], new \DateTimeImmutable('-5 days')],
            [$usuarios[2], $libros[2], new \DateTimeImmutable('-2 days')],
        ];

        foreach ($prestamos as [$usuario, $libro, $fecha]) {
            $p = new Prestamo();
            $p->setUsuario($usuario);
            $p->setLibro($libro);
            $p->setFechaPrestamo($fecha);
            $manager->persist($p);
        }

        // Guardar todo
        $manager->flush();
    }
}
<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para la entidad Libro.
 *
 * Permite realizar consultas relacionadas con los libros almacenados
 * en la base de datos. Hereda los métodos estándar de Doctrine, como:
 *  - find()
 *  - findOneBy()
 *  - findAll()
 *  - findBy()
 *
 * Si en el futuro necesitas consultas personalizadas sobre libros,
 * este es el lugar donde implementarlas.
 */
class LibroRepository extends ServiceEntityRepository
{
    /**
     * Constructor del repositorio.
     *
     * @param ManagerRegistry $registry Gestiona las conexiones y metadatos de Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }
}
<?php

namespace App\Repository;

use App\Entity\Prestamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para la entidad Prestamo.
 *
 * Proporciona acceso a los métodos estándar de Doctrine para consultar
 * préstamos en la base de datos, como:
 *  - find()
 *  - findOneBy()
 *  - findAll()
 *  - findBy()
 *
 * Aquí es donde se pueden añadir consultas personalizadas relacionadas
 * con los préstamos, por ejemplo, obtener préstamos por rango de fechas
 * o filtrar por usuario.
 */
class PrestamoRepository extends ServiceEntityRepository
{
    /**
     * Constructor del repositorio.
     *
     * @param ManagerRegistry $registry Gestiona las conexiones y metadatos de Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prestamo::class);
    }
}
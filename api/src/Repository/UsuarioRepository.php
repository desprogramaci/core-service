<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repositorio para la entidad Usuario.
 *
 * Proporciona acceso a los métodos estándar de Doctrine para consultar
 * usuarios en la base de datos, como:
 *  - find()
 *  - findOneBy()
 *  - findAll()
 *  - findBy()
 *
 * Aquí es donde se pueden añadir consultas personalizadas relacionadas
 * con usuarios, por ejemplo búsquedas por DNI o filtros avanzados.
 */
class UsuarioRepository extends ServiceEntityRepository
{
    /**
     * Constructor del repositorio.
     *
     * @param ManagerRegistry $registry Gestiona las conexiones y metadatos de Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }
}
<?php

namespace App\Repository;

use App\Entity\CuentaZoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CuentaZoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method CuentaZoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method CuentaZoom[]    findAll()
 * @method CuentaZoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentaZoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CuentaZoom::class);


    }

}

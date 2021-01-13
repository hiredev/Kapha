<?php

namespace App\Repository;

use App\Entity\Maestro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Maestro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Maestro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Maestro[]    findAll()
 * @method Maestro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaestroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maestro::class);
    }

    // /**
    //  * @return Maestro[] Returns an array of Maestro objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Maestro
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

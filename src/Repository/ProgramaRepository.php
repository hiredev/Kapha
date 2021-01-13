<?php

namespace App\Repository;

use App\Entity\Programa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Programa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programa[]    findAll()
 * @method Programa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programa::class);
    }

    // /**
    //  * @return Programa[] Returns an array of Programa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Programa
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

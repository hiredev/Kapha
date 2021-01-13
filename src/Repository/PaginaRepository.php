<?php

namespace App\Repository;

use App\Entity\Pagina;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pagina|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pagina|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pagina[]    findAll()
 * @method Pagina[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaginaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pagina::class);
    }

    // /**
    //  * @return Pagina[] Returns an array of Pagina objects
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
    public function findOneBySomeField($value): ?Pagina
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

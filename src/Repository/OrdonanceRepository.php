<?php

namespace App\Repository;

use App\Entity\Ordonance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordonance>
 */
class OrdonanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonance::class);
    }

    //    /**
    //     * @return Ordonance[] Returns an array of Ordonance objects
    //     */
    public function ordonance()
    {
        return $this->createQueryBuilder('o')
        ->leftJoin('o.medicament', 'm')
        ->addSelect('m')
        ->leftJoin('o.Patien','p')
        ->addSelect('p')
        ->leftJoin('o.medecin','med')
        ->addSelect('med')
        ->getQuery()
        ->getResult()
        ;
    }
    public function findeByForme()
    {
        return $this->createQueryBuilder('o')
        ->leftJoin('o.medicament','m')
        ->addSelect('m')
        ->leftJoin('m.forme', 'forme')
        ->addSelect('forme')
        ->getQuery()
        ->getResult()
        ;

    }
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ordonance
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

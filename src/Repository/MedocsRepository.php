<?php

namespace App\Repository;

use App\Entity\Medocs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medocs>
 */
class MedocsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medocs::class);
    }

    //    /**
    //     * @return Medocs[] Returns an array of Medocs objects
    //     */

    public function medocs()
    {
        return $this->createQueryBuilder('m')
        ->select('m','f')
        ->join('m.forme','f')
        ->addSelect('f')
        ->getQuery()
        ->getResult();
    }
    public function findExpiringInOneMonth()
    {
        $now = new \DateTime();
        $oneMonthLater = (clone $now)->modify('+1 month');

        return $this->createQueryBuilder('m')
            ->where('m.date_exp BETWEEN :now AND :oneMonthLater')
            ->setParameter('now', $now)
            ->setParameter('oneMonthLater', $oneMonthLater)
            ->getQuery()
            ->getResult();
    }
    public function findExpiringInTwoMonths()
    {
        $now = new \DateTime();
        $twoMonthsLater = (clone $now)->modify('+2 month');

        return $this->createQueryBuilder('m')
            ->where('m.date_exp BETWEEN :now AND :twoMonthsLater')
            ->setParameter('now', $now)
            ->setParameter('twoMonthsLater', $twoMonthsLater)
            ->getQuery()
            ->getResult();
    }
    function findByForme($forme)
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.forme', 'f')
            ->where('f.forme = :forme')
            ->setParameter('forme', $forme)
            ->getQuery()
            ->getResult();
    }
    public function findByDescription(string $keyword)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Medocs
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

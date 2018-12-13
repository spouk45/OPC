<?php

namespace App\Repository;

use App\Entity\TypeInterventionReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeInterventionReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeInterventionReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeInterventionReport[]    findAll()
 * @method TypeInterventionReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeInterventionReportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeInterventionReport::class);
    }

//    /**
//     * @return TypeInterventionReport[] Returns an array of TypeInterventionReport objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeInterventionReport
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\InterventionReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InterventionReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionReport[]    findAll()
 * @method InterventionReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionReportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InterventionReport::class);
    }

    public function findLastPlannedMaintenance(Customer $customer): ?InterventionReport
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.customer = :customer')
            ->andWhere('i.plannedDate IS NOT NULL')
            ->andWhere('i.realisedDate IS NULL')
            ->setParameter('customer', $customer)
            ->orderBy('i.plannedDate', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return InterventionReport[] Returns an array of InterventionReport objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    */

    /*
    public function findOneBySomeField($value): ?InterventionReport
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
<?php

namespace App\Repository;

use App\Entity\CustomerHeating;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method CustomerHeating|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerHeating|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerHeating[]    findAll()
 * @method CustomerHeating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerHeatingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomerHeating::class);
    }

    public function findByAnniversaryDate2MonthNext(){
        $date = new DateTime();
        $date2 = new DateTime();
        $date2->modify('+2 month');
        return $this->createQueryBuilder('ch')
            ->andWhere('ch.anniversaryDate > :date')
            ->andWhere('ch.anniversaryDate < :date2')
            ->setParameter('date',$date)
            ->setParameter('date2',$date2)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return CustomerHeating[] Returns an array of CustomerHeating objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomerHeating
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

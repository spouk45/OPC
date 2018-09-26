<?php

namespace App\Repository;

use App\Entity\HeatingSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HeatingSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeatingSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeatingSource[]    findAll()
 * @method HeatingSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeatingSourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HeatingSource::class);
    }

//    /**
//     * @return HeatingSource[] Returns an array of HeatingSource objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HeatingSource
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

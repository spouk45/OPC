<?php

namespace App\Repository;

use App\Entity\HeatingType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HeatingType|null find($id, $lockMode = null, $lockVersion = null)
 * @method HeatingType|null findOneBy(array $criteria, array $orderBy = null)
 * @method HeatingType[]    findAll()
 * @method HeatingType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeatingTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HeatingType::class);
    }

//    /**
//     * @return HeatingType[] Returns an array of HeatingType objects
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
    public function findOneBySomeField($value): ?HeatingType
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

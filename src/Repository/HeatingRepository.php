<?php

namespace App\Repository;

use App\Entity\Heating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Heating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Heating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Heating[]    findAll()
 * @method Heating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeatingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Heating::class);
    }

//    /**
//     * @return Heating[] Returns an array of Heating objects
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
    public function findOneBySomeField($value): ?Heating
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

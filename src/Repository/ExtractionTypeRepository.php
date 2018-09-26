<?php

namespace App\Repository;

use App\Entity\ExtractionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExtractionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtractionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtractionType[]    findAll()
 * @method ExtractionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtractionTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExtractionType::class);
    }

//    /**
//     * @return ExtractionType[] Returns an array of ExtractionType objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtractionType
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

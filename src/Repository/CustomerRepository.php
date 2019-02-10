<?php

namespace App\Repository;

use App\Entity\Customer;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Customer::class);
    }

//    /**
//     * @return Customer[] Returns an array of Customer objects
//     */

    public function findLikeName($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :name')
            ->setParameter('name', '%'.$value.'%')
            ->orderBy('c.name', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findNeedMaintenanceForMap($numberOfMonthBeforeAlert = 3)
    {
        // récupération du numéro de mois

        $dateMin = new DateTime('-'.$numberOfMonthBeforeAlert.' month');
        $customers = $this->createQueryBuilder('c')
            ->andWhere('c.contractFinish = false')
            ->andWhere('c.lastMaintenanceDate < :dateMin')
            ->andWhere('c.plannedMaintenanceDate IS NULL')
            ->setParameter('dateMin', $dateMin)
            ->orderBy('c.name', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;


        $data = [];
        $data['plannedMaintenance']= [];
        /** @var Customer $customer */
        foreach ($customers as $customer){
            if($customer->getPlannedMaintenanceDate() != null){
                $data['plannedMaintenance'][]=$customer;
            }else{
                $data[$customer->getAnniversaryDate()][]= $customer;
            }
        }

        return $data;
    }

    public function findNeedMaintenance()
    {
        // récupération du numéro de mois

//        $dateMin = new DateTime('-'.$numberOfMonthBeforeAlert.' month');
        $customers = $this->createQueryBuilder('c')
            ->andWhere('c.contractFinish = false')
            ->orderBy('c.plannedMaintenanceDate', 'ASC')
            ->orderBy('c.lastMaintenanceDate', 'ASC')
            ->setMaxResults(300)
            ->getQuery()
            ->getResult()
        ;

        $data = [];
        $data['plannedMaintenance'] = [];
        $data['outdated'] = [];
        $date = new DateTime();

        /** @var Customer $customer */
        foreach ($customers as $customer) {
            // calcul de lastmaintenance > 12 mois
            $interval = $customer->getLastMaintenanceDate()->diff($date);
            if ($customer->getPlannedMaintenanceDate() != null) {
                $data['plannedMaintenance'][] = $customer;
            } else {
                if($interval->days > 365){
                    $data['outdated'][] = $customer;
                }else{
                    $data[$customer->getAnniversaryDate()][] = $customer;
                }
            }
        }

        return $data;
    }
    /*
    public function findOneBySomeField($value): ?Customer
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

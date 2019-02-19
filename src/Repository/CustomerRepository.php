<?php

namespace App\Repository;

use App\Entity\Customer;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\Date;

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
//            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findNeedMaintenanceForMap()
    {
        $date = new DateTime();
        $monthActual = (int) $date->format('m');
        $monthActual == 12 ? $nextMonth = 1 : $nextMonth = $monthActual + 1;

        $customerFiltered = $this->findNeedMaintenance();

        $warning = [];
        $success = [];
        if(isset($customerFiltered[$monthActual])){
            $warning = $customerFiltered[$monthActual];
        }
        if(isset($customerFiltered[$nextMonth])){
            $success = $customerFiltered[$nextMonth];
        }

        // regroupement des customers par color
        $group['blue'] =  $customerFiltered['plannedMaintenance'];
        $group['red'] = $customerFiltered['outdated'];
        $group['orange'] = $warning;
        $group['green'] =  $success;

        // contruction des données pour JSON
        $data = [
            'blue' => [],
            'red' => [],
            'orange' => [],
            'green' => [],
            ];
        foreach ($group as $color => $customers){

            /** @var  Customer $customer */
            foreach ($customers as $customer){
                $data[$color][] = [
                    'fullName' => $customer->getName() . ' ' . $customer->getFirstname(),
                    'fullAdress' => $customer->getFullAdress(),
                    'location' => $customer->getCoordGPS(),
                    'annivContratDate' => $customer->getAnniversaryMonth(),
                    'lastMaintenanceDate' => $customer->getLastMaintenanceDate()->format('d M Y'),
                    'planned' => $customer->getPlannedMaintenanceDate() !== null ? $customer->getPlannedMaintenanceDate()->format('d M Y') : null,
                    'id' => $customer->getId(),
                ];
            }
        }

        return $data;
    }

    public function findNeedMaintenance()
    {
        // récupération du numéro de mois
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

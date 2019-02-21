<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Services\DateUtils;
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


    public function countCustomers(array $data = [])
    {
        $prep = $this->createQueryBuilder('c')->select('COUNT(c)');
        if (count($data) > 0) {
            $prep->andWhere('c.' . $data['field'] . ' = :value')
                ->setParameter('value', $data['value']);
        }
        $result = $prep->getQuery()->getSingleResult();
        return $result[1];
    }

    public function countPlannedDate()
    {
        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.plannedMaintenanceDate IS NOT NULL')
            ->getQuery()->getSingleResult();

        return $result[1];
    }

    public function countCustomerToPlanIn2NextMonth()
    {
        $thisMonth = date("m");
        $month1 = $thisMonth + 1 == 13 ? 1 : $thisMonth + 1;
        $month2 = $month1 + 1 == 13 ? 1 : $thisMonth + 1;

        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->orWhere('c.anniversaryDate = :month')
            ->orWhere('c.anniversaryDate = :month1')
            ->orWhere('c.anniversaryDate = :month2')
            ->andWhere('c.plannedMaintenanceDate IS NULL')
            ->setParameter('month', $thisMonth)
            ->setParameter('month1', $month1)
            ->setParameter('month2', $month2)
            ->getQuery()->getSingleResult();

        return $result[1];
    }

    public function countCustomerDontHaveMaintenanceSinceOneYear()
    {
        $date = new DateTime('-1 year');
        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.lastMaintenanceDate <= :date')
            ->orWhere('c.lastMaintenanceDate IS NULL')
            ->setParameter('date', $date)
            ->getQuery()->getSingleResult();

        return $result[1];
    }

    public function countCoordGPSNull()
    {
        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.coordGPS IS NULL')
            ->getQuery()->getSingleResult();

        return $result[1];
    }

    public function getCountByAnniversaryDate()
    {
        $results = $this->createQueryBuilder('c')
            ->select('COUNT(c) as countCustomer,c.anniversaryDate')
            ->groupBy('c.anniversaryDate')
            ->getQuery()->getResult();

        $data = [];
        $dateUtils = new DateUtils();
        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = ['countCustomer' => 0, 'anniversaryDate' => $dateUtils->getMonthToShortString($i)];
        }
        foreach ($results as $result) {
            $data[$result['anniversaryDate']]['countCustomer'] = $result['countCustomer'];
        }
        return $data;
    }

    public function findLikeName($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :name')
            ->setParameter('name', '%' . $value . '%')
            ->orderBy('c.name', 'ASC')
//            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }

    public function findNeedMaintenanceForMap()
    {
        $date = new DateTime();
        $monthActual = (int)$date->format('m');
        $monthActual == 12 ? $nextMonth = 1 : $nextMonth = $monthActual + 1;

        $customerFiltered = $this->findNeedMaintenance();

        $warning = [];
        $success = [];
        if (isset($customerFiltered[$monthActual])) {
            $warning = $customerFiltered[$monthActual];
        }
        if (isset($customerFiltered[$nextMonth])) {
            $success = $customerFiltered[$nextMonth];
        }

        // regroupement des customers par color
        $group['blue'] = $customerFiltered['plannedMaintenance'];
        $group['red'] = $customerFiltered['outdated'];
        $group['orange'] = $warning;
        $group['green'] = $success;

        // contruction des données pour JSON
        $data = [
            'blue' => [],
            'red' => [],
            'orange' => [],
            'green' => [],
        ];
        foreach ($group as $color => $customers) {

            /** @var  Customer $customer */
            foreach ($customers as $customer) {
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
            ->getResult();

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
                if ($interval->days > 365) {
                    $data['outdated'][] = $customer;
                } else {
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

<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 05/12/2018
 * Time: 21:34
 */

namespace App\Services;


use App\Entity\Customer;
use App\Repository\InterventionReportRepository;
use DateTime;

class ExtractCustomer
{
    const LIMIT_DATE_MAINTENANCE = '+2 month'; // period between now and limit date maintenance
    const WARNING_PERIOD = '+1 month';

    public function extractCustomerNeedMaintenance(array $customers): array
    {
        $customersNeedMaintenance = [];
        if (!empty(array_filter($customers))) {
            $now = new DateTime();
            $dateNext2Month = new DateTime('+2 month');
            $date6MonthAgo = new DateTime('-6 month');
            $date10MonthAgo = new DateTime('-10 month');
            /** @var Customer $customer */
            foreach ($customers as $customer) {
                $lastMaintenance = $customer->getLastMaintenanceDate();
                if($lastMaintenance == null){
                    $lastMaintenance = $customer->getContractDate();
                }
                /** @var  DateTime $anniversary */
                $anniversary = $customer->getAnniversaryDate();

                // on regle la date d'anniversaire sur l'année en cours
                $anniversary = $this->setAnniversaryDateToActual($anniversary);

                if (
                    ($lastMaintenance < $date6MonthAgo &&  // si la derniere maitenance date de plus 6 mois
                        $anniversary > $now && // si la date d'anniv est à venir
                        $anniversary < $dateNext2Month // si la date d'anniv est dans les 2 mois suivant
                    )
                    || $lastMaintenance < $date10MonthAgo// si la derniere maitenance date de plus 10 mois
                ) {
                    $customersNeedMaintenance[] = $customer; // si toutes les conditions : on ajoute le client au tableau
                }
            }
        }
        return $customersNeedMaintenance;
    }

    public function setAnniversaryDateToActual(DateTime $anniversary): DateTime
    {
        $now = new DateTime();
        if ($anniversary->format('Y') < $now->format('Y')) {
            $nbYear = $now->format('Y') - $anniversary->format('Y');
            $anniversary->modify('+' . $nbYear . ' year');

            $temp = new DateTime();
            $temp->modify('-10 month');
            if($anniversary < $temp){
                $anniversary->modify('+1 year');
            }
        }
        return $anniversary;
    }

    /**
     * @param array $customers
     * @param InterventionReportRepository $interventionReportRepository
     * @return array
     */
    public function filterCustomersByPeriodMaintenance(array $customers,InterventionReportRepository $interventionReportRepository): array
    {
        $now = new DateTime();
        $warningPeriod = new DateTime(self::WARNING_PERIOD);

        $data = ['green' => [], 'orange' => [], 'red' => [], 'blue' =>[]];

        /** @var Customer $customer */
        foreach ($customers as $customer) {
            /** @var DateTime $anniversary */
            $anniversary = $customer->getAnniversaryDate();
            $anniversary = $this->setAnniversaryDateToActual($anniversary);
            $lastMaintenancePlannedDate = $interventionReportRepository->findLastPlannedMaintenance($customer);
            if($lastMaintenancePlannedDate != null){ //comparer si planifier ou non
                $data['blue'][] = $customer;
            } else if ($anniversary < $now) {
                $data['red'][] = $customer;
            } else if ($anniversary < $warningPeriod) {
                $data['orange'][] = $customer;
            } else {
                $data['green'][] = $customer;
            }
        }
        return $data;
    }

    public function createCustomerForJsonExportToMap(Customer $customer): array
    {
        $data = [
            'fullName' => $customer->getName() . ' ' . $customer->getFirstname(),
            'fullAdress' => $customer->getFullAdress(),
            'location' => $customer->getCoordGPS(),
            'annivContratDate' => $customer->getAnniversaryDate()->format('d M'),
            'id' => $customer->getId(),
        ];

        return $data;
    }
}
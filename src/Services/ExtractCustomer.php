<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 05/12/2018
 * Time: 21:34
 */

namespace App\Services;


use App\Entity\Customer;
use App\Entity\CustomerHeating;
use DateTime;

class ExtractCustomer
{
    const LIMIT_DATE_MAINTENANCE = '+2 month'; // period between now and limit date maintenance
    const ORANGE = '+1 month';

    public function extractCustomerNeedMaintenance(array $customerHeatings): array
    {
        if (!empty(array_filter($customerHeatings))) {
            $customers = [];

            $now = new DateTime();
            $dateNext2Month = new DateTime('+2 month');
            $date6MonthAgo = new DateTime('-6 month');
            $date10MonthAgo = new DateTime('-10 month');
            /** @var CustomerHeating $customerHeating */
            foreach ($customerHeatings as $customerHeating) {
                $lastMaintenance = $customerHeating->getLastMaintenanceDate();
                if($lastMaintenance == null){
                    $lastMaintenance = $customerHeating->getContractDate();
                }
                /** @var  DateTime $anniversary */
                $anniversary = $customerHeating->getAnniversaryDate();

                // on regle la date d'anniversaire sur l'année en cours
                $anniversary = $this->setAnniversaryDateToActual($anniversary);

                if (
                    ($lastMaintenance < $date6MonthAgo &&  // si la derniere maitenance date de plus 6 mois
                        $anniversary > $now && // si la date d'anniv est à venir
                        $anniversary < $dateNext2Month // si la date d'anniv est dans les 2 mois suivant
                    )
                    || $lastMaintenance < $date10MonthAgo// si la derniere maitenance date de plus 10 mois
                ) {
                    $customers[$customerHeating->getCustomer()->getId()] = $customerHeating->getCustomer(); // si toutes les conditions : on ajoute le client au tableau
                }
            }

            sort($customers);

            return $customers;
        }
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

    public function filterCustomersByPeriodMaintenance(array $customers): array
    {
        $now = new DateTime();
        $orange = new DateTime(self::ORANGE);

        $data = ['green' => [], 'orange' => [], 'red' => []];

        /** @var Customer $customer */
        foreach ($customers as $customer) {
            /** @var DateTime $anniversary */
            $anniversary = $customer->getCustomerHeatings()[0]->getAnniversaryDate();
            $anniversary = $this->setAnniversaryDateToActual($anniversary);
            if ($anniversary < $now) {
                $data['red'][] = $customer;
            } else if ($anniversary < $orange) {
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
            'adress' => $customer->getFullAdress(),
            'location' => $customer->getCoordGPS(),
            'annivContratDate' => $customer->getCustomerHeatings()[0]->getAnniversaryDate()->format('d M'),
            'id' => $customer->getId(),
        ];

        return $data;
    }
}
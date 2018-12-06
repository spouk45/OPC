<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 05/12/2018
 * Time: 21:34
 */

namespace App\Services;


use App\Entity\CustomerHeating;
use DateTime;

class ExtractCustomer
{

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
                /** @var  DateTime $anniversary */
                $anniversary = $customerHeating->getAnniversaryDate();

                // on regle la date d'anniversaire sur l'année en cours
                if ($anniversary->format('Y') < $now->format('Y')) {
                    $nbYear = $now->format('Y') - $anniversary->format('Y');
                    $anniversary->modify('+' . $nbYear . ' year');
                }

                if (
                    ($lastMaintenance < $date6MonthAgo &&  // si la derniere maitenance date de plus 6 mois
                        $anniversary > $now && $anniversary < $dateNext2Month) // si la date d'anniv est dans les 2 mois suivant
                    || $lastMaintenance < $date10MonthAgo// si la derniere maitenance date de plus 10 mois
                ) {
                    $customers[$customerHeating->getCustomer()->getId()] = $customerHeating->getCustomer(); // si toutes les conditions : on ajoute le client au tableau
                }
            }

            sort($customers);
            return $customers;
        }
    }
}
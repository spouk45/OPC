<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 02/02/2019
 * Time: 12:32
 */

namespace App\Services;


class DateUtils
{
    const DATE_INT_TO_STRING = [
            1 => 'Janvier',
            2 => 'Février'  ,
            3 => 'Mars'  ,
            4 => 'Avril',
            5 => 'Mai' ,
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

    const DATE_INT_TO_SHORT_STRING = [
        1 => 'Jan',
        2 => 'Fév'  ,
        3 => 'Mars'  ,
        4 => 'Avr',
        5 => 'Mai' ,
        6 => 'Juin',
        7 => 'Juil',
        8 => 'Août',
        9 => 'Sept',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Déc'
    ];

    function getMonthToString(int $month): String
    {
        return self::DATE_INT_TO_STRING[$month];
    }

    function getMonthToShortString(int $month): String
    {
        return self::DATE_INT_TO_SHORT_STRING[$month];
    }

    function getAllMonths():array
    {
        return self::DATE_INT_TO_STRING;
    }

}
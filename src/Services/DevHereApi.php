<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 09/10/2018
 * Time: 22:38
 */

namespace App\Services;


class DevHereApi
{
    const API_KEY = 'API_KEY_DEV_HERE';
    const APP_CODE = 'APP_CODE_DEV_HERE';

    /**
     * @param $address
     * @return array
     */
    public function geocodeAddress($address): array
    {

        //valeurs vide par défaut
        $data = ['location' => '', 'error' => ''];
        //on formate l'adresse
        $address = urlencode($address . ' France');
        $url = 'https://geocoder.api.here.com/6.2/geocode.json?app_id=' . getenv(self::API_KEY) . '&app_code=' . getenv(self::APP_CODE) . '&searchtext=' . $address;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        curl_close($ch);
        dd($res);

        $json = file_get_contents($url);
//        //on enregistre les résultats recherchés
        $json = json_decode($json);
        if (count($json->Response->View) > 0) {
            $res = $json->Response->View[0]->Result[0];
            $data['location'] = ['lat' => $res->Location->DisplayPosition->Latitude,
                'long' => $res->Location->DisplayPosition->Longitude,
            ];

        } else {
            $data['error'] = 'Pas de résultats trouvés';
        }
        return $data;
    }

}
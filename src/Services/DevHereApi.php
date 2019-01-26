<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 09/10/2018
 * Time: 22:38
 */

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\Session;

class DevHereApi
{
    private $apiKey;
    private $appCode;
    const CERT_SSL_IS_ACTIVE = 'CERT_SSL_IS_ACTIVE';

    public function __construct()
    {
        $session = new Session();
        $this->apiKey = $session->get('configs')['API_KEY_DEV_HERE'];
        $this->appCode = $session->get('configs')['APP_CODE_DEV_HERE'];
    }

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
        $url = 'https://geocoder.api.here.com/6.2/geocode.json?app_id=' . $this->apiKey . '&app_code=' . $this->appCode . '&searchtext=' . $address;

        try {

            $client = new Client();
            $option = [
                'verify' => getenv(self::CERT_SSL_IS_ACTIVE) == '1' ? true : false,
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ];
            $res = $client->request('GET', $url, $option);

            if (!$res || $res->getStatusCode() != '200') {
                throw new Exception('Une erreur est survenue. Code: ' . $res->getStatusCode());
            }

            $json = $res->getBody()->getContents();

//        //on enregistre les résultats recherchés
            $json = json_decode($json);
            if (count($json->Response->View) == 0) {
                throw new Exception('Pas de résultats trouvés');
            }

            $res = $json->Response->View[0]->Result[0];
            $data['location'] = [
                'lat' => $res->Location->DisplayPosition->Latitude,
                'lng' => $res->Location->DisplayPosition->Longitude,
            ];
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
        } catch (RequestException  $e) {
            dump($e->getMessage());
            if ($code = $e->getResponse() && $code = $e->getResponse()->getStatusCode()) {
                $data['error'] = 'API DEV HERE : Une erreur est survenue. Code: ' . $code;
            } else {
                $data['error'] = $e->getMessage();
            }

        } catch (GuzzleException $e) {
            $data['error'] = $e->getMessage();
        }
        return $data;
    }


}
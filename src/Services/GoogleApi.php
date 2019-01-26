<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 01/11/2018
 * Time: 12:25
 */

namespace App\Services;


use Symfony\Component\HttpFoundation\Session\Session;

class GoogleApi
{
    const API_KEY = 'API_KEY_GOOGLE_MAPS';
    private $apiKey;

    public function __construct()
    {
        $session = new Session();
        $this->apiKey = $session->get('configs')['API_KEY_GOOGLE_MAPS'];
    }

}
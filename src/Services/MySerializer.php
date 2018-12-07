<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 07/12/2018
 * Time: 16:28
 */

namespace App\Services;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MySerializer
{
    public function serialize($dataToSerialize){
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = array()) {
            if( method_exists($object, 'getName')){
                return $object->getName();
            }else{
                return $object->getId();
            }
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($dataToSerialize, 'json');

        return $jsonContent;
    }

}
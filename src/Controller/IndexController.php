<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 17/09/2018
 * Time: 20:30
 */

namespace App\Controller;

use App\Entity\CustomerHeating;
use App\Services\ExtractCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class IndexController extends Controller
{
    /**
     * @param ExtractCustomer $extractCustomer
     * @return Response
     * @Route("/", name="index")
     */
    public function index(ExtractCustomer $extractCustomer)
    {
        //$view = $this->forward('App\Controller\CustomerController::showCustomersNeedMaintenanceForMap');
        $customerHeatings = $this->getDoctrine()->getRepository(CustomerHeating::class)->findByContractFinish(false);
        $customers = $extractCustomer->extractCustomerNeedMaintenance($customerHeatings);

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        // all callback parameters are optional (you can omit the ones you don't use)
        $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = array()) {
            if( method_exists($object, 'getName')){
                return $object->getName();
            }else{
                return $object->getId();
            }
        });

        $serializer = new Serializer(array($normalizer), array($encoder));

        $jsonContent = $serializer->serialize($customers, 'json');
        dump($jsonContent);
        return $this->render('index.html.twig', [
                'customers' => $jsonContent,
            ]
        );
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('Admin/index.html.twig');
    }

}
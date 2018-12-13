<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 17/09/2018
 * Time: 20:30
 */

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\CustomerHeating;
use App\Repository\InterventionReportRepository;
use App\Services\ExtractCustomer;
use App\Services\MySerializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @param ExtractCustomer $extractCustomer
     * @param MySerializer $mySerializer
     * @param InterventionReportRepository $interventionReportRepository
     * @return Response
     * @Route("/", name="index")
     */
    public function index(ExtractCustomer $extractCustomer, MySerializer $mySerializer,InterventionReportRepository $interventionReportRepository)
    {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findByContractFinish(false);
        // récupération de la liste de client ayant besoin d'une maintenance
        $customersNeedMaintenance = $extractCustomer->extractCustomerNeedMaintenance($customers);

        // trie par coleur des clients en fonction de sa date de contrat
        $customersNeedMaintenanceColored = $extractCustomer->filterCustomersByPeriodMaintenance($customersNeedMaintenance,$interventionReportRepository);
        dump($customersNeedMaintenanceColored);
        return $this->render('index.html.twig', [
                'customersNeedMaintenanceColored' => $customersNeedMaintenanceColored,
            ]
        );
    }

    /**
     * @param ExtractCustomer $extractCustomer
     * @param MySerializer $mySerializer
     * @param InterventionReportRepository $interventionReportRepository
     * @return Response
     * @Route("/map", name="map")
     */
    public function map(ExtractCustomer $extractCustomer, MySerializer $mySerializer,InterventionReportRepository $interventionReportRepository)
    {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findByContractFinish(false);
        // récupération de la liste de client ayant besoin d'une maintenance
        $customersNeedMaintenance = $extractCustomer->extractCustomerNeedMaintenance($customers);

        // trie par coleur des clients en fonction de sa date de contrat
        $customersNeedMaintenanceColored = $extractCustomer->filterCustomersByPeriodMaintenance($customersNeedMaintenance,$interventionReportRepository);

        $customers = [];
        foreach ($customersNeedMaintenanceColored as $color => $value) {
            foreach ($value as $customer) {
                $customerFo = $extractCustomer->createCustomerForJsonExportToMap($customer);
                $customerFo['color'] = $color;
                $customers[] = $customerFo;
            }
        }

        $customers = $mySerializer->serialize($customers);
        return $this->render('map.html.twig', [
                'customers' => $customers,
                'apiKey' => getenv('API_KEY_GOOGLE_MAPS'),
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

    /**
     * @param Customer $customer
     * @param InterventionReportRepository $interventionReportRepository
     * @return Response
     * @Route("/test/{id}", name="test")
     */


}
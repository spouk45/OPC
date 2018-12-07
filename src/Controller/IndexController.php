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
use App\Services\MySerializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @param ExtractCustomer $extractCustomer
     * @return Response
     * @Route("/", name="index")
     */
    public function index(ExtractCustomer $extractCustomer, MySerializer $mySerializer)
    {
        //$view = $this->forward('App\Controller\CustomerController::showCustomersNeedMaintenanceForMap');
        $customerHeatings = $this->getDoctrine()->getRepository(CustomerHeating::class)->findByContractFinish(false);
        // récupération de la liste de client ayant besoin d'une maintenance
        $customers = $extractCustomer->extractCustomerNeedMaintenance($customerHeatings);

        // trie par coleur des clients en fonction de sa date de contrat
        $customersFiltered = $extractCustomer->filterCustomersByPeriodMaintenance($customers);

        $customers = [];
        foreach ($customersFiltered as $color => $value) {
            foreach ($value as $customer) {
                $customerFo = $extractCustomer->createCustomerForJsonExportToMap($customer);
                $customerFo['color'] = $color;
                $customers[] = $customerFo;
            }
        }

        $customers = $mySerializer->serialize($customers);
        return $this->render('index.html.twig', [
                'customers' => $customers,
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
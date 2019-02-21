<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 17/09/2018
 * Time: 20:30
 */

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\ConfigurationRepository;
use App\Repository\CustomerRepository;
use App\Repository\InterventionReportRepository;
use App\Services\DateUtils;
use App\Services\MySerializer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    private $interventionReportRepository;

    public function __construct(InterventionReportRepository $interventionReportRepository)
    {
        $this->interventionReportRepository = $interventionReportRepository;
    }

    /**
     * @param DateUtils $DateUtils
     * @return Response
     * @Route("/customerNeedMaintenance", name="customerNeedMaintenance")
     */
    public function needMaintenance(DateUtils $DateUtils)
    {
        $customersFiltered = $this->getDoctrine()->getRepository(Customer::class)->findNeedMaintenance();
        $customersPlanned = $customersFiltered['plannedMaintenance'];
        $customersOutdated = $customersFiltered['outdated'];
        unset($customersFiltered['plannedMaintenance']);
        unset($customersFiltered['outdated']);
        $customersByMonth = $customersFiltered;
        $date = new DateTime();
        $monthActual = (int)$date->format('m');
        // trie par couleur des clients en fonction de sa date de contrat
        return $this->render('customerNeedMaintenance.html.twig', [
                'customersByMonth' => $customersByMonth,
                'customersPlanned' => $customersPlanned,
                'customersOutdated' => $customersOutdated,
                'dateUtils' => $DateUtils,
                'monthActual' => $monthActual,
            ]
        );
    }

    /**
     * @return Response
     * @param CustomerRepository $customerRepository
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(CustomerRepository $customerRepository)
    {
        $nbCustomers = $customerRepository->countCustomers();
        $nbCustomersOnContract = $customerRepository->countCustomers([
            'field' => 'contractFinish',
            'value' => false
        ]);
        $nbCustomersNotOnContract = $nbCustomers - $nbCustomersOnContract;
        $nbPlannedMaintenance = $customerRepository->countPlannedDate();
        $nbCustomersToPlan = $customerRepository->countCustomerToPlanIn2NextMonth();
        $nbCustomerDontHaveMaintenanceSinceOneYear = $customerRepository->countCustomerDontHaveMaintenanceSinceOneYear();
        $nbCoordGPSNull = $customerRepository->countCoordGPSNull();

        $dataCustomers = [
            'nbCustomers' => $nbCustomers,
            'nbCustomersOnContract' => $nbCustomersOnContract,
//            'nbCustomersNotOnContract' => $nbCustomersNotOnContract,
            'nbPlannedMaintenance' => $nbPlannedMaintenance,
            'nbCustomersToPlan' => $nbCustomersToPlan,
            'nbCustomerDontHaveMaintenanceSinceOneYear' => $nbCustomerDontHaveMaintenanceSinceOneYear,
            'nbCoordGPSNull' => $nbCoordGPSNull,
        ];

        $nbCustomersByAnniversaryDate = $customerRepository->getCountByAnniversaryDate();

        return $this->render('dashboard.html.twig', [
                'dataCustomers' => $dataCustomers,
                'nbCustomersByAnniversaryDate' => $nbCustomersByAnniversaryDate,
            ]
        );
    }

    /**
     * @param MySerializer $mySerializer
     * @return Response
     * @Route("/map", name="map")
     */
    public function map(MySerializer $mySerializer)
    {
        $session = new Session();

        $customers = $this->getDoctrine()->getRepository(Customer::class)->findNeedMaintenanceForMap(false);
        $customers = $mySerializer->serialize($customers);
        return $this->render('map.html.twig', [
                'customers' => $customers,
                'apiKey' => $session->get('configs')['API_KEY_GOOGLE_MAPS'],
            ]
        );
    }

    /**
     * @param ConfigurationRepository $configurationRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/", name="index")
     */
    public function controlConfig(ConfigurationRepository $configurationRepository)
    {
        $session = new Session();
        $configs = $configurationRepository->findAll();
        $dataConfigs = [];

        foreach ($configs as $config) {
            $dataConfigs[$config->getName()] = $config->getValue();
        }

        $error = [];
        if (!isset($dataConfigs['API_KEY_GOOGLE_MAPS'])) {
            $error[] = 'API_KEY_GOOGLE_MAPS';
        }
        if (!isset($dataConfigs['API_KEY_DEV_HERE'])) {
            $error[] = 'API_KEY_DEV_HERE';
        }
        if (!isset($dataConfigs['APP_CODE_DEV_HERE'])) {
            $error[] = 'APP_CODE_DEV_HERE';
        }
        if (count($error) > 0) {
            $this->addFlash('danger', 'Les clés : ' . implode(', ', $error) . ' n\'existent pas dans la base.');
        }

        $session->set('configs', $dataConfigs);

        return $this->redirectToRoute('dashboard');
    }
}
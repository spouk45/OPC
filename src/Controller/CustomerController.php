<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\CustomerHeating;
use App\Form\CustomerSearchType;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Services\DevHereApi;
use App\Services\ExtractCustomer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @return Response
     * @Route("/", name="customer_index", methods={"GET","POST"})
     */
    public function index(Request $request, CustomerRepository $customerRepository): Response
    {
        $customerSearch = $this->createForm(CustomerSearchType::class);
        $customerSearch->handleRequest($request);
        if ($customerSearch->isSubmitted() && $customerSearch->isValid()) {
            $customers = $customerRepository->findLikeName($request->request->get('customer_search')['name']);
        } else {
            $customers = $customerRepository->findAll();
        }

        return $this->render('customer/index.html.twig',
            [
                'customers' => $customers,
                'customerSearch' => $customerSearch->createView(),
            ]);
    }

    /**
     * @param Request $request
     * @param DevHereApi $devHereApi
     * @return Response
     * @Route("/new", name="customer_new", methods="GET|POST")
     */
    public function new(Request $request, DevHereApi $devHereApi): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer->makeAdress();
            $resApi = $devHereApi->geocodeAddress($customer->getAdress());
            if($resApi['error'] == null ){
                $location = $resApi['location'];
                $customer->setCoordGPS(($location));
                $em = $this->getDoctrine()->getManager();
                $em->persist($customer);
                $em->flush();
                $this->addFlash('success','client ajouté avec succès');
                return $this->redirectToRoute('customer_index');
            }else{
                $this->addFlash('danger',$resApi['error']);
            }
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="customer_show", methods="GET")
     */
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', ['customer' => $customer]);
    }

    /**
     * @Route("/{id}/edit", name="customer_edit", methods="GET|POST")
     */
    public function edit(Request $request, Customer $customer): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_edit', ['id' => $customer->getId()]);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="customer_delete", methods="DELETE")
     */
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customer);
            $em->flush();
        }

        return $this->redirectToRoute('customer_index');
    }


//    /**
//     * @param ExtractCustomer $extractCustomer
//     * @return \Symfony\Component\HttpFoundation\JsonResponse
//     * @Route("/map", name="customer_delete", methods="GET|POST")
//     */
//    public function getCustomersNeedMaintenanceForMap(ExtractCustomer $extractCustomer)
//    {
//         // on récupère la liste de client en contrat
//         $customerHeatings = $this->getDoctrine()->getRepository(CustomerHeating::class)->findByContractFinish(false);
//         $customers = $extractCustomer->extractCustomerNeedMaintenance($customerHeatings);
//
//         // on reconstruit un tableau pour avoir seulement les données nécessaires
//
//            return $this->json($customers);
//    }

}

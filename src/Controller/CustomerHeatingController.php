<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\CustomerHeating;
use App\Entity\Heating;
use App\Form\CustomerHeatingType;
use App\Form\HeatingType;
use App\Repository\CustomerHeatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer/heating")
 */
class CustomerHeatingController extends AbstractController
{
    /**
     * @Route("/", name="customer_heating_index", methods="GET")
     */
    public function index(CustomerHeatingRepository $customerHeatingRepository): Response
    {
        return $this->render('customer_heating/index.html.twig', ['customer_heatings' => $customerHeatingRepository->findAll()]);
    }

    /**
     * @Route("/new/{id}", name="customer_heating_new", methods="GET|POST")
     */
    public function new(Request $request,Customer $customer): Response
    {
        $customerHeating = new CustomerHeating();
        $form = $this->createForm(CustomerHeatingType::class, $customerHeating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerHeating->setCustomer($customer);
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerHeating);
            $em->flush();

            return $this->redirectToRoute('customer_show',['id' => $customer->getId()]);
        }


        return $this->render('customer_heating/new.html.twig', [
            'customer_heating' => $customerHeating,
            'form' => $form->createView(),
            'customer' => $customer,
        ]);
    }

    /**
     * @Route("/{id}", name="customer_heating_show", methods="GET")
     */
    public function show(CustomerHeating $customerHeating): Response
    {
        return $this->render('customer_heating/show.html.twig', ['customer_heating' => $customerHeating]);
    }

    /**
     * @Route("/{id}/edit", name="customer_heating_edit", methods="GET|POST")
     */
    public function edit(Request $request, CustomerHeating $customerHeating): Response
    {
        $form = $this->createForm(CustomerHeatingType::class, $customerHeating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_heating_edit', ['id' => $customerHeating->getId()]);
        }

        return $this->render('customer_heating/edit.html.twig', [
            'customer_heating' => $customerHeating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="customer_heating_delete", methods="DELETE")
     */
    public function delete(Request $request, CustomerHeating $customerHeating): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customerHeating->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerHeating);
            $em->flush();
        }

        return $this->redirectToRoute('customer_heating_index');
    }
}

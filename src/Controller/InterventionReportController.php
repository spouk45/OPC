<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\InterventionReport;
use App\Form\InterventionReportEditType;
use App\Form\InterventionReportType;
use App\Repository\InterventionReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intervention/report")
 */
class InterventionReportController extends AbstractController
{
    /**
     * @param InterventionReportRepository $interventionReportRepository
     * @param Customer $customer
     * @return Response
     * @Route("/{customer}", name="intervention_report_list", methods="GET")
     */
    public function index(InterventionReportRepository $interventionReportRepository, Customer $customer): Response
    {
        return $this->render('intervention_report/index.html.twig',
            [
                'intervention_reports' => $interventionReportRepository->findByCustomer($customer),
                'customer' => $customer
            ]);
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return Response
     * @Route("/new/{customer}", name="intervention_report_new", methods="GET|POST")
     */
    public function new(Request $request, Customer $customer): Response
    {
        $interventionReport = new InterventionReport();
        $form = $this->createForm(InterventionReportType::class, $interventionReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $interventionReport->setCustomer($customer);
            $plannedOrRealised = $form->get('plannedOrRealised')->getViewData();
            // si planifié ou realisé selectionné, on set la date du param correspondant
            $plannedOrRealised ? $interventionReport->setRealisedDate($form->get('dateForSelected')->getNormData()) :
                $interventionReport->setPlannedDate($form->get('dateForSelected')->getNormData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($interventionReport);
            $em->flush();

            // mise à jour du plannedMaintenanceDate du client
            $customer->setPlannedMaintenanceDate($interventionReport->getPlannedDate());
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('intervention_report_list', ['customer' => $customer->getId()]);
        }

        return $this->render('intervention_report/new.html.twig', [
            'intervention_report' => $interventionReport,
            'customerId' => $customer->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="intervention_report_show", methods="GET")
     */
    public function show(InterventionReport $interventionReport): Response
    {
        return $this->render('intervention_report/show.html.twig', ['intervention_report' => $interventionReport]);
    }

    /**
     * @Route("/{id}/edit", name="intervention_report_edit", methods="GET|POST")
     */
    public function edit(Request $request, InterventionReport $interventionReport): Response
    {
        $form = $this->createForm(InterventionReportEditType::class, $interventionReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // si maintenance à été modifié
            if($interventionReport->getTypeInterventionReport() == "maintenance" &&
                $interventionReport->getCustomer()->getPlannedMaintenanceDate() != $interventionReport->getPlannedDate()){
                $em = $this->getDoctrine()->getManager();
                /** @var Customer $customer */
                $customer = $interventionReport->getCustomer();
                $customer->setPlannedMaintenanceDate($interventionReport->getPlannedDate());
                $em->persist($customer);
                $em->flush();
            }

            // si status à été modifié en réalisé


            // si status à été modifié en plannifié
            

            return $this->redirectToRoute('intervention_report_list', ['customer' => $interventionReport->getCustomer()->getId()]);
        }
        dump($interventionReport);
        return $this->render('intervention_report/edit.html.twig', [
            'intervention_report' => $interventionReport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="intervention_report_delete", methods="DELETE")
     */
    public function delete(Request $request, InterventionReport $interventionReport): Response
    {
        if ($this->isCsrfTokenValid('delete' . $interventionReport->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($interventionReport);
            $em->flush();
        }

        return $this->redirectToRoute('intervention_report_index');
    }
}

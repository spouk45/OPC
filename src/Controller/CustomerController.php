<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Image;
use App\Form\CustomerSearchType;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Services\FileUploader;
use App\Services\DevHereApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @param FileUploader $fileUploader
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
            $resApi = $devHereApi->geocodeAddress($customer->getFullAdress());
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
     * @param Request $request
     * @param Customer $customer
     * @param FileUploader $fileUploader
     * @return Response
     * @Route("/{id}/edit", name="customer_edit", methods="GET|POST")
     */
    public function edit(Request $request, Customer $customer, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('images')->getData();
            $em = $this->getDoctrine()->getManager();

            if (!empty($files)) {
                /** @var UploadedFile $file */
                foreach ($files as $file) {
                    /** @var array $newFile */
                    $newFile = $fileUploader->upload($file);
                    $imagesToAdd = new Image();
                    $imagesToAdd->setName($newFile['fileName']);
                    $imagesToAdd->setOriginalName($newFile['originalName']);
                    $imagesToAdd->setUploadDate(new DateTime());
                    $customer->addImagesLink($imagesToAdd);
                    $em->persist($imagesToAdd);
                }
            }

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

}

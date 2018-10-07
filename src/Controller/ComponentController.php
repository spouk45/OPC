<?php

namespace App\Controller;

use App\Entity\Extraction;
use App\Entity\HeatingSource;
use App\Entity\HeatingType;
use App\Form\ExtractionType;
use App\Form\HeatingSourceType;
use App\Form\HeatingType1Type;
use App\Repository\ExtractionTypeRepository;
use App\Repository\HeatingSourceRepository;
use App\Repository\HeatingTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComponentController extends AbstractController
{
    /**
     * @param ExtractionTypeRepository $extractionTypeRepository
     * @param HeatingSourceRepository $heatingSourceRepository
     * @param HeatingTypeRepository $heatingTypeRepository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/component", name="component")
     */
    public function index(
        ExtractionTypeRepository $extractionTypeRepository,
        HeatingSourceRepository $heatingSourceRepository,
        HeatingTypeRepository $heatingTypeRepository,
        Request $request
    ){

        $extraction = new Extraction();
        $formExtraction = $this->createForm(ExtractionType::class, $extraction);
        $formExtraction->handleRequest($request);

        // Extrattion
        if ($formExtraction->isSubmitted() && $formExtraction->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($extraction);
            $em->flush();

            return $this->redirectToRoute('component');
        }

        $heatingType = new HeatingType();
        $formHeatingType = $this->createForm(HeatingType1Type::class, $heatingType);
        $formHeatingType->handleRequest($request);

        // HeatingType
        if ($formHeatingType->isSubmitted() && $formHeatingType->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($heatingType);
            $em->flush();

            return $this->redirectToRoute('component');
        }

        $source = new HeatingSource();
        $formSourceType = $this->createForm(HeatingSourceType::class, $source);
        $formSourceType->handleRequest($request);

        // Source
        if ($formSourceType->isSubmitted() && $formSourceType->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($source);
            $em->flush();

            return $this->redirectToRoute('component');
        }


        return $this->render('component/index.html.twig', [
            'extractions' => $extractionTypeRepository->findAll(),
            'sources' => $heatingSourceRepository->findAll(),
            'heatingTypes' => $heatingTypeRepository->findAll(),
            'formExtraction' => $formExtraction->createView(),
            'formHeatingType' => $formHeatingType->createView(),
            'formSourceType' => $formSourceType->createView(),
        ]);
    }


}

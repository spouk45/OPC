<?php

namespace App\Controller;

use App\Form\ExtractionType;
use App\Repository\ExtractionTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/extraction")
 */
class ExtractionController extends AbstractController
{
    /**
     * @Route("/", name="extraction_type_index", methods="GET")
     */
    public function index(ExtractionTypeRepository $extractionTypeRepository): Response
    {
        return $this->render('component/extraction/index', ['extraction_types' => $extractionTypeRepository->findAll()]);
    }

    /**
     * @Route("/new", name="extraction_type_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $extractionType = new ExtractionType();
        $form = $this->createForm(ExtractionType::class, $extractionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($extractionType);
            $em->flush();

            return $this->redirectToRoute('extraction_type_index');
        }

        return $this->render('extraction_type/new.html.twig', [
            'extraction_type' => $extractionType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="extraction_type_show", methods="GET")
     */
    public function show(ExtractionType $extractionType): Response
    {
        return $this->render('extraction_type/show.html.twig', ['extraction_type' => $extractionType]);
    }

    /**
     * @Route("/{id}/edit", name="extraction_type_edit", methods="GET|POST")
     */
    public function edit(Request $request, ExtractionType $extractionType): Response
    {
        $form = $this->createForm(ExtractionType::class, $extractionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('extraction_type_edit', ['id' => $extractionType->getId()]);
        }

        return $this->render('extraction_type/edit.html.twig', [
            'extraction_type' => $extractionType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="extraction_type_delete", methods="DELETE")
     */
    public function delete(Request $request, ExtractionType $extractionType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$extractionType->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($extractionType);
            $em->flush();
        }

        return $this->redirectToRoute('extraction_type_index');
    }
}

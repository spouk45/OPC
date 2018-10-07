<?php

namespace App\Controller;

use App\Entity\HeatingSource;
use App\Form\HeatingSourceType;
use App\Repository\HeatingSourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/heating/source")
 */
class HeatingSourceController extends AbstractController
{
    /**
     * @Route("/", name="heating_source_index", methods="GET")
     */
    public function index(HeatingSourceRepository $heatingSourceRepository): Response
    {
        return $this->render('heating_source/index.html.twig', ['heating_sources' => $heatingSourceRepository->findAll()]);
    }

    /**
     * @Route("/new", name="heating_source_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $heatingSource = new HeatingSource();
        $form = $this->createForm(HeatingSourceType::class, $heatingSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($heatingSource);
            $em->flush();

            return $this->redirectToRoute('heating_source_index');
        }

        return $this->render('heating_source/new.html.twig', [
            'heating_source' => $heatingSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heating_source_show", methods="GET")
     */
    public function show(HeatingSource $heatingSource): Response
    {
        return $this->render('heating_source/show.html.twig', ['heating_source' => $heatingSource]);
    }

    /**
     * @Route("/{id}/edit", name="heating_source_edit", methods="GET|POST")
     */
    public function edit(Request $request, HeatingSource $heatingSource): Response
    {
        $form = $this->createForm(HeatingSourceType::class, $heatingSource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('heating_source_edit', ['id' => $heatingSource->getId()]);
        }

        return $this->render('heating_source/edit.html.twig', [
            'heating_source' => $heatingSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heating_source_delete", methods="DELETE")
     */
    public function delete(Request $request, HeatingSource $heatingSource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$heatingSource->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($heatingSource);
            $em->flush();
        }

        return $this->redirectToRoute('heating_source_index');
    }
}

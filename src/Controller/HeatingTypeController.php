<?php

namespace App\Controller;

use App\Entity\HeatingType;
use App\Form\HeatingType1Type;
use App\Repository\HeatingTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/heating/type")
 */
class HeatingTypeController extends AbstractController
{
    /**
     * @Route("/", name="heating_type_index", methods="GET")
     */
    public function index(HeatingTypeRepository $heatingTypeRepository): Response
    {
        return $this->render('heating_type/index.html.twig', ['heating_types' => $heatingTypeRepository->findAll()]);
    }

    /**
     * @Route("/new", name="heating_type_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $heatingType = new HeatingType();
        $form = $this->createForm(HeatingType1Type::class, $heatingType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($heatingType);
            $em->flush();

            return $this->redirectToRoute('heating_type_index');
        }

        return $this->render('heating_type/new.html.twig', [
            'heating_type' => $heatingType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heating_type_show", methods="GET")
     */
    public function show(HeatingType $heatingType): Response
    {
        return $this->render('heating_type/show.html.twig', ['heating_type' => $heatingType]);
    }

    /**
     * @Route("/{id}/edit", name="heating_type_edit", methods="GET|POST")
     */
    public function edit(Request $request, HeatingType $heatingType): Response
    {
        $form = $this->createForm(HeatingType1Type::class, $heatingType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('heating_type_edit', ['id' => $heatingType->getId()]);
        }

        return $this->render('heating_type/edit.html.twig', [
            'heating_type' => $heatingType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heating_type_delete", methods="DELETE")
     */
    public function delete(Request $request, HeatingType $heatingType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$heatingType->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($heatingType);
            $em->flush();
        }

        return $this->redirectToRoute('heating_type_index');
    }
}

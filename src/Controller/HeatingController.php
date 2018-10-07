<?php

namespace App\Controller;

use App\Entity\Heating;
use App\Form\HeatingType;
use App\Repository\ExtractionTypeRepository;
use App\Repository\HeatingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/heating")
 */
class HeatingController extends AbstractController
{
    /**
     * @Route("/", name="heating_index", methods="GET")
     */
    public function index(HeatingRepository $heatingRepository): Response
    {
        return $this->render('heating/index.html.twig', ['heatings' => $heatingRepository->findAll()]);
    }

    /**
     * @Route("/new", name="heating_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $heating = new Heating();
        $form = $this->createForm(HeatingType::class, $heating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($heating);
            $em->flush();

            return $this->redirectToRoute('heating_index');
        }

        return $this->render('heating/new.html.twig', [
            'heating' => $heating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heating_show", methods="GET")
     */
    public function show(Heating $heating): Response
    {
        return $this->render('heating/show.html.twig', ['heating' => $heating]);
    }

    /**
     * @Route("/{id}/edit", name="heating_edit", methods="GET|POST")
     */
    public function edit(Request $request, Heating $heating): Response
    {
        $form = $this->createForm(HeatingType::class, $heating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('heating_edit', ['id' => $heating->getId()]);
        }

        return $this->render('heating/edit.html.twig', [
            'heating' => $heating,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heating_delete", methods="DELETE")
     */
    public function delete(Request $request, Heating $heating): Response
    {
        if ($this->isCsrfTokenValid('delete'.$heating->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($heating);
            $em->flush();
        }

        return $this->redirectToRoute('heating_index');
    }


}

<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Services\FileUploader;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @param Request $request
     * @param Customer $customer
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/image/add/{id}", name="add_image")
     */
    public function add(Request $request, Customer $customer, FileUploader $fileUploader)
    {
        $form = $this->createFormBuilder($customer)
            ->add('images', FileType::class,
            [
                'label' => 'Images',
                'required' => true,
                'multiple' => true,
                'mapped' => true,
            ])
            ->getForm();

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

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Images ajoutés avec success.'
            );
            }
            return $this->redirectToRoute('show_images', ['id' => $customer->getId()]);

        }
        return $this->render('image/add.html.twig', [
            'form' => $form->createView(),
            'customer' => $customer,
        ]);
    }

    /**
     * @param Customer $customer
     * @param ImageRepository $imageRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/image/show/{id}", name="show_images")
     */
    function gallery(Customer $customer,  ImageRepository $imageRepository)
    {
        return $this->render('image/gallery.html.twig', [
            'customer' => $customer,
        ]);
    }
}

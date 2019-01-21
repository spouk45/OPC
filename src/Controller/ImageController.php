<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Services\FileUploader;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/image/show/{id}", name="show_images")
     */
    function gallery(Customer $customer)
    {
        return $this->render('image/gallery.html.twig', [
            'customer' => $customer,
        ]);
    }


    /**
     * @param Request $request
     * @param Customer $customer
     * @param ImageRepository $imageRepository
     * @return Response
     * @Route("/image/delete/{id}}", name="delete_images", methods="DELETE")
     */
    public function delete(Request $request, Customer $customer, ImageRepository $imageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $images = $request->request->get('imagesToDelete');
            $images = json_decode ($images);
            $fileSystem = new Filesystem();
            dump($images);
            foreach ($images as $imageId){
                // recherche de l'image
                /** @var Image $image */
                $image = $imageRepository->findOneById($imageId);
                dump($image);
                try {
                    $fileSystem->remove(['/public/uploads/images/'.$image->getName()]);
                }catch (Exception $e){
                    $e->getMessage();
                    dd("error");
                }
                dd("ok");
                $em->remove($image);
            }
            $em->flush();
        }

        return $this->redirectToRoute('show_images',['id'=> $customer->getId()]);
    }
}

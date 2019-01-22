<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('Admin/user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/Admin/user/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('danger', 'Nom d\'utilisateur déjà existant');
                return $this->render('Admin/user/new.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('Admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/Admin/user/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
            dd($user);
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('Admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Admin/user/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/profil", name="user_profil"), methods="GET|POST"
     */
    public function profil(UserInterface $user,Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
            dd($user);
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('Profil/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}

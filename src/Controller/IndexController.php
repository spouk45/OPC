<?php
/**
 * Created by PhpStorm.
 * User: spouk
 * Date: 17/09/2018
 * Time: 20:30
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $coords = $this->forward('App\Controller\CustomerController::getCoordFor2NextMonthContract');
        return $this->render('index.html.twig', [
                'coords' => $coords->getContent(),
            ]
        );
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('Admin/index.html.twig');
    }

}
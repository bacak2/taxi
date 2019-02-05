<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/public"
 * )
 *
 * Class PublicController
 * @package AppBundle\Controller
 */
class PublicController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_public_driver"
     * )
     */
    public function driverAction()
    {

        $driver = $this->getUser();

        //dump($driver);

        return $this->render('@App/public/driver/index.html.twig', []);
    }
}
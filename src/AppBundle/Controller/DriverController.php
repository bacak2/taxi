<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Form\DriverForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DriverController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/kierowcy"
 * )
 */
class DriverController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_drivers"
     * )
     */
    public function indexAction()
    {
        return $this->render('@App/driver/index.html.twig', array());
    }

    /**
     * @Route(
     *     "/get",
     *     name="route_drivers_api_get"
     * )
     */
    public function getDriversAction()
    {
        $repo = $this->getDoctrine()->getRepository(Driver::class);

        return $this->json(array(
            'data' => $repo->getDriversList()
        ));
    }

    /**
     * @Route(
     *     "/dodaj",
     *     name="route_driver_add"
     * )
     */
    public function addAction(Request $request)
    {
        $driver = new Driver();
        $form = $this->createForm(DriverForm::class, $driver);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                if(count($driver->getBlockade()) > 0)
                {
                    foreach ($driver->getBlockade() as $blockade)
                    {
                        $blockade->setUser($this->getUser());
                    }
                }
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($driver);
                $manager->flush();

                return $this->redirectToRoute('route_driver_add');
            }
        }

        return $this->render('@App/driver/add_new_driver.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(
     *     "/edycja/{id}",
     *     name="route_driver_edit"
     * )
     */
    public function editAction(Request $request, $id)
    {
        $driver = $this->getDoctrine()->getRepository(Driver::class)->find($id);
        if(NULL == $driver)
        {
            $driver = new Driver();
        }
        $form = $this->createForm(DriverForm::class, $driver);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                if(count($driver->getBlockade()) > 0)
                {
                    foreach ($driver->getBlockade() as $blockade)
                    {
                        $blockade->setUser($this->getUser());
                    }
                }
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($driver);
                $manager->flush();

                return $this->redirectToRoute('route_drivers');
            }
        }

        return $this->render('@App/driver/add_new_driver.html.twig', array(
            'form' => $form->createView()
        ));
    }
}

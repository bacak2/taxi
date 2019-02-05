<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Agreement;
use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\Firm;
use AppBundle\Form\ClientForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ClientController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/firmy"
 * )
 */
class ClientController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_firms"
     * )
     */
    public function indexAction()
    {
        return $this->render('@App/client/index.html.twig', array());
    }

    /**
     * @Route(
     *     "/get",
     *     name="route_firm_get"
     * )
     */
    public function getFirmAction()
    {
        $repo = $this->getDoctrine()->getRepository(Client::class);

        return $this->json(array(
            'data' => $repo->getClientsList()
        ));
    }

    /**
     * @Route(
     *     "/edycja/{id}",
     *     name="route_firm_edit"
     * )
     * TODO Metoda zrobiona tylko testowo - do dopracowania
     */
    public function editAction(Request $request, $id)
    {
        $client = $this->getDoctrine()->getRepository(Client::class)->find($id);
        if(NULL == $client)
        {
            return $this->createNotFoundException("Nie ma takiej firmy w systemie!");
        }

        $form = $this->createForm(ClientForm::class, $client);
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($client);
                $manager->flush();

                return $this->redirectToRoute('route_firm_add');
            }
        }

        return $this->render('@App/client/client.html.twig', array(
            'form' => $form->createView(),
            'clientId' => $id
        ));
    }

    /**
     * @Route(
     *     "/nowa-firma",
     *     name="route_firm_add"
     * )
     */
    public function addAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientForm::class, $client);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($client);
                $manager->flush();

                return $this->redirectToRoute('route_firm_add');
            }
        }

        return $this->render('@App/client/client.html.twig', array(
            'form' => $form->createView()
        ));
    }
}

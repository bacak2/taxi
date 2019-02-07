<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\Form\SettingsFormData;
use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\TaxiSettings;
use AppBundle\Form\Settings\ParamForm;
use AppBundle\Form\Settings\ParamsForm;
use AppBundle\Service\TaxiSettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SettingsController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/ustawienia"
 * )
 */
class SettingsController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_taxi_settings"
     * )
     */
    public function taxiSettingsAction(Request $request, TaxiSettingsService $settings)
    {
        $params = $this->getDoctrine()->getRepository(TaxiSettings::class)
            ->find(1);
        $form = $this->createForm(ParamsForm::class, $params);
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($settings);
                $manager->flush();
                /**
                 * TODO:: Uwazgledniac tylko transakcje ktore nie zostaly rozliczone
                 */

                return $this->redirectToRoute('route_settings_params');
            }
        }

        return $this->render('@App/settings/params.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function paramsAction()
    {

    }

    /**
     * @Route(
     *     "/data",
     *     name="route_date_settings"
     * )
     */
    public function dateSettingsAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $this->get('session')->set('date', $request->request->get('date'));
            $this->addFlash('info', 'Zmieniono datę');
        }

        $setDate = $this->get('session')->get('date') ?? date("Y-m-d");

        return $this->render('@App/settings/date.html.twig', array(
            'setDate' => $setDate
        ));
    }
}

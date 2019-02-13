<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\Form\SettingsFormData;
use AppBundle\Entity\Params\Param;
use AppBundle\Entity\Params\ParamCategory;
use AppBundle\Entity\Dictionary\DictionaryParam;
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
        $formDictionary = $this->createForm(ParamForm::class);
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

        return $this->render('@App/settings/base.html.twig', array(
            'formParams' => $form->createView(),
            'formDictionary' => $formDictionary->createView()
        ));
    }

    /**
     * @Route(
     *     "/slownik-pobierz",
     *     name="route_dictionary_api_get"
     * )
     */
    public function dictionaryGetAction()
    {
        $repo = $this->getDoctrine()->getRepository(DictionaryParam::class);

        return $this->json(array(
            'data' => $repo->getParamList()
        ));
    }

    /**
     * @Route(
     *     "/slownik-dodaj",
     *     name="route_dictionary_api_add"
     * )
     */
    public function dictionaryAddAction(Request $request, TaxiSettingsService $settings)
    {
        $params = $request->request->get('formData');
        $settings->addDictionaryParam($params);

        return $this->json(array(
            'data' => true
        ));
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

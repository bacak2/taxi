<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterDetail;
use AppBundle\Entity\Enumerator;
use AppBundle\Form\CashRegister\KWForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class KWController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/kw"
 * )
 */
class KWController extends Controller
{
    /**
     * @Route(
     *     "/lista",
     *     name="route_kw_list"
     * )
     */
    public function listAction()
    {
        return $this->render('@App/kw/kw_list.html.twig', array());
    }

    /**
     * AJAX - Lista KW
     * @Route(
     *     "lista-ajax",
     *     name="route_kw_ajax"
     * )
     */
    public function getKWlist(Request $request)
    {
        $dateFrom = $request->request->get('dateFrom');
        $dateTo = $request->request->get('dateTo');

        $rows = $this->getDoctrine()
            ->getRepository(CashRegister::class)
            ->getListByDateAndType(array(
                'type' => CashRegister::TYPE_KW,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ));
        return $this->json(array(
            "data" => $rows
        ));
    }

    /**
     * @Route(
     *     "/wystaw/standard",
     *     name="route_kw_standard_add"
     * )
     */
    public function addStandard(Request $request)
    {
        $cashRegister = new CashRegister();
        $cashRegister
            ->setTransactionDate((new \DateTime())->format('Y-m-d H:i:s'))
            ->setUser($this->getUser());

        $form = $this->createForm(KWForm::class, $cashRegister);
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($cashRegister->getDriver() != null && $cashRegister->getDriver()->getBlocked() == true)
            {
                $this->addFlash('warning', 'Kierowca ma założoną blokadę!');
                return $this->redirectToRoute('route_kw_standard_add');
            }else{
                if($form->isSubmitted() && $form->isValid())
                {
                    $manager = $this->getDoctrine()->getManager();

                    $reportDate = $this->getDoctrine()
                        ->getRepository('AppBundle:CashRegister\CashRegisterReport')
                        ->findOneBy(['reportForDate' => $cashRegister->getTransactionDate()]);
                    if(is_null($reportDate)) {
                        $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)
                            ->getEnumerator(Enumerator::TYPE_KW, $this->getUser());

                        $cashRegister
                            ->setCashRegisterNumber($enumerator->getCashRegisterNumber());
                        $details = new CashRegisterDetail();
                        $details
                            ->setCashRegister($cashRegister)
                            ->setParam($form->get('param')->getData())
                            ->setQuantity(1)
                            ->setBrutto($form->get('amount')->getData());

                        $manager->persist($cashRegister);
                        $manager->persist($details);
                        $manager->persist($enumerator);
                        $manager->flush();

                        $this->addFlash('print', $cashRegister->getId());
                        return $this->redirectToRoute('route_kw_standard_add');
                    }
                    else
                    {
                        $this->addFlash('error', 'Dla tej daty dzień jest już zamknięty');
                        return $this->redirectToRoute('route_kw_standard_add');
                    }
                }
            }
        }
        return $this->render('@App/kw/kw_standard_add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}

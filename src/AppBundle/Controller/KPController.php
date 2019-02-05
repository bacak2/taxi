<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterDetail;
use AppBundle\Entity\Enumerator;
use AppBundle\Form\CashRegister\KPForm;
use AppBundle\Form\CashRegister\KPWarehouseForm;
use AppBundle\Form\CashRegister\KWForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\ApiTaxi360\Driver;

/**
 * Class KPController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/kp"
 * )
 */
class KPController extends Controller
{
    /**
     * @Route(
     *     "/lista",
     *     name="route_kp_list"
     * )
     */
    public function listAction()
    {
        return $this->render('@App/kp/kp_list.html.twig', array());
    }

    /**
     * @Route(
     *     "/api/lista",
     *     name="route_kp_ajax"
     * )
     */
    public function getKPList(Request $request)
    {
        $dateFrom = $request->request->get('dateFrom');
        $dateTo = $request->request->get('dateTo');

        $rows = $this->getDoctrine()
            ->getRepository(CashRegister::class)
            ->getListByDateAndType(array(
                'type' => CashRegister::TYPE_KP,
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
     *     name="route_kp_standard_add",
     * )
     */
    public function addStandard(Request $request)
    {
        $cashRegister = new CashRegister();
        $cashRegister->setTransactionDate((new \DateTime())->format('Y-m-d H:i:s'));

        $form = $this->createForm(KPForm::class, $cashRegister);
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $manager = $this->getDoctrine()->getManager();
                $reportDate = $this->getDoctrine()
                    ->getRepository('AppBundle:CashRegister\CashRegisterReport')
                    ->findOneBy(['reportForDate' => $cashRegister->getTransactionDate()]);

                if(is_null($reportDate)) {
                    $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)
                        ->getEnumerator(Enumerator::TYPE_KP, $this->getUser());
                    $cashRegister
                        ->setCashRegisterNumber($enumerator->getCashRegisterNumber())
                        ->setUser($this->getUser());
                    $detail = new CashRegisterDetail();
                    $detail
                        ->setCashRegister($cashRegister)
                        ->setParam($form->get('param')->getData())
                        ->setQuantity(1)
                        ->setBrutto($form->get('amount')->getData());

                    $manager->persist($enumerator);
                    $manager->persist($cashRegister);
                    $manager->persist($detail);
                    $manager->flush();

                    $this->addFlash('print', $cashRegister->getId());
                    return $this->redirectToRoute('route_kp_standard_add');
                }
                else
                {
                    $this->addFlash('error', 'Dla tej daty dzień jest już zamknięty');
                    return $this->redirectToRoute('route_kp_standard_add');
                }
            }
        }

        return $this->render('@App/kp/kp_standard_add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(
     *     "/magazyn",
     *     name="route_kp_warehouse"
     * )
     */
    public function warehouseAction(Request $request)
    {
        $cashRegister = new CashRegister();
        $form = $this->createForm(KPWarehouseForm::class, $cashRegister);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)
                    ->getEnumerator(Enumerator::TYPE_KP, $this->getUser());

                $cashRegister->setUser($this->getUser())
                    ->setTransactionDate(new \DateTime())
                    ->setTransactionType(CashRegister::TYPE_KP)
                    ->setCashRegisterNumber($enumerator->getCashRegisterNumber())
                    ->setTitle($form->get('kp_type')->getData()->getName())
                    ;

                $em = $this->getDoctrine()->getManager();
                $em->persist($cashRegister);
                $em->persist($enumerator);
                $em->flush();

                $this->addFlash('print', $cashRegister->getId());
                return $this->redirectToRoute('route_kp_warehouse');
            }
        }
        return $this->render('@App/kp/kp_warehouse.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route(
     *     "/api/driver-note-kp",
     *     name="driver_note_kp_ajax"
     * )
     */
    public function getDriverNote(Request $request)
    {
        $driverId = $request->query->get('driverId');
        $repo = $this->getDoctrine()->getRepository(Driver::class);

        return $this->json(array(
            "data" => $repo->getDriverNote($driverId)
        ));
    }

}

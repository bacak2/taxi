<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\Settlement;
use AppBundle\Form\AddVoucherForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VoucherController
 * @package AppBundle\Controller
 *
 * @Route(
 *     "/voucher"
 * )
 */
class VoucherController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_voucher_list"
     * )
     */
    public function indexAction(Request $request)
    {
        $transaction = new Transaction();
        $form = $this->createForm(AddVoucherForm::class, $transaction);

        if($request->isMethod('POST') && !$request->isXmlHttpRequest())
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $transaction->setIsVoucher919(true)
                    ->setAuthCode('000000')
                    ->setAuthDate(new \DateTime())
                    ->setTransactionStatus('ACCEPTED')
                    ->setTransactionStatusCode('000')
                    ->setVat(0.08)
                    ->setManual(true)
                    ->setTaxiDriverId($transaction->getDriverId()->getTaxiDriverId())
                    ->setTaxiClientId($transaction->getClientId()->getTaxiClientId())
                    ->setPrice($transaction->getTotalAmount())
                    ->setTip(0)
                    ->setOriginalLicenseNumber($transaction->getDriverId()->getLicenseNumber())
                    ->setTransactionType('bezgotowka')
                    ->setCardType('VOUCHER919')
                    ->setDriverAmount($transaction->getTotalAmount())
                    ->setPrice($transaction->getTotalAmount())
                    ->setCorpoAliasUsed('NO')
                    ->setTaxiCorpoAliasId(13)
                    ->setUser($this->getUser())
                ;
                $settlement = new Settlement();
                $settlement->setUser($this->getUser())
                    ->setTotalAmount($transaction->getTotalAmount())
                    ->setPercentage($form->get('percentage')->getData())
                    ->setIsSettled(false)
                    ->setTransaction($transaction);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($transaction);
                $manager->persist($settlement);
                $manager->flush();

                return $this->redirectToRoute('route_voucher_list');
            }
        }

        $now = new \DateTime();
        $interval = new \DateInterval('PT672H');
        $monthInterval = new \DateTime();
        $monthInterval->sub($interval);

        return $this->render('@App/voucher/add_new_voucher.html.twig', array(
            'form' => $form->createView(),
            'now' => $now,
            'minus30Days' => $monthInterval
        ));
    }

    /**
     * @Route(
     *     "/api/voucher",
     *     name="route_voucher_api_get"
     * )
     */
    public function getVoucherList(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $params = array(
            'dateFrom' => $request->request->get('dateFrom'),
            'dateTo' => $request->request->get('dateTo')
        );

        return $this->json(array(
            'data' => $repo->getVoucherList($params)
        ));
    }
}

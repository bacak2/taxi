<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterDetail;
use AppBundle\Entity\Enumerator;
use AppBundle\Entity\Settlement;
use AppBundle\Form\SettlementTransForm;
use AppBundle\Form\TransactionFileForm;
use AppBundle\Form\UnassignedDriverForm;
use AppBundle\Service\ImportTransaction;
use Doctrine\Common\Annotations\Annotation\Enum;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @Route(
 *     "/transakcje"
 * )
 */
class TransactionController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_transactions"
     * )
     */
    public function transactionAction(Request $request)
    {
        return $this->render('@App/transaction/list_transaction.html.twig', array());
    }

    /**
     * AJAX - Transakcje
     * @Route(
     *     "/api/get",
     *     name="route_transaction_api_get"
     * )
     */
    public function getTransactionsAction(Request $request)
    {
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $params = array(
            "dateFrom" => $request->request->get("dateFrom"),
            "dateTo" => $request->request->get("dateTo"),
            "licenseNumber" => $request->request->get("licenseNumber"),
            "clientNumber" => $request->request->get("clientNumber"),
            "cardNumber" => $request->request->get("cardNumber")
        );

        return $this->json(array(
            'data' => $transactionRepo->findTransactions($params, false)
        ));
    }

    /**
     * @Route(
     *     "/api/change-percentage",
     *     name="route_transaction_api_change_percentage"
     * )
     */
    public function changePercentage(Request $request)
    {
        $settlementId = $request->request->get('settlementId');
        $percentage = $request->request->get('percentage');

        $settlement = $this->getDoctrine()->getRepository(Settlement::class)
            ->find($settlementId);

        $settlement->setPercentage(($percentage/100));
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($settlement);
        $manager->flush();

        return $this->json([
            'status' => 'success'
        ]);
    }

    /**
     * @Route(
     *     "/api/reset-transaction",
     *     name="route_transaction_api_reset_trans"
     * )
     */
    public function resetTransaction(Request $request)
    {
        $settlementId = $request->request->get('settlementId');

        /**
         * @var Settlement $settlement
         */
        $settlement = $this->getDoctrine()->getRepository(Settlement::class)
            ->find($settlementId);


        if($settlement->getIsReseted() == true)
        {
            $totalAmount = $settlement->getResetTotalAmount();
            $settledAmount = $settlement->getResetSettledAmount();
            $withFee  = $settlement->getResetAmountWithFee();
            $settlement
                ->setIsReseted(false)
                ->setResetTotalAmount(0)
                ->setResetSettledAmount(0)
                ->setResetAmountWithFee(0)
                ->setTotalAmount($totalAmount)
                ->setSettledAmount($settledAmount)
                ->setAmountWithFee($withFee);
        }else{
            $totalAmount = $settlement->getTotalAmount();
            $settledAmount = $settlement->getSettledAmount();
            $withFee  = $settlement->getAmountWithFee();
            $settlement
                ->setIsReseted(true)
                ->setResetTotalAmount($totalAmount)
                ->setResetSettledAmount($settledAmount)
                ->setResetAmountWithFee($withFee)
                ->setTotalAmount(0)
                ->setSettledAmount(0)
                ->setAmountWithFee(0);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($settlement);
        $manager->flush();

        return $this->json([
            'status' => 'success'
        ]);
    }

    /**
     * Rozliczenia transakcji
     * @Route(
     *     "/rozliczenia",
     *     name="route_transaction_settlement"
     * )
     */
    public function settlementAction(Request $request)
    {
        $cashRegister = new CashRegister();
        $form = $this->createForm(SettlementTransForm::class, $cashRegister);

        if($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($cashRegister->getDriver() != null
                && $cashRegister->getDriver()->getBlocked() == true) {
                $this->addFlash('warning', 'Kierowca ma założoną blokadę!');
                return $this->redirectToRoute('route_kw_standard_add');
            } else {
                if ($form->isSubmitted() && $form->isValid()) {
                    $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)
                        ->getEnumerator(Enumerator::TYPE_KW, $this->getUser());

                    $cashRegister
                        ->setUser($this->getUser())
                        ->setTransactionType(CashRegister::TYPE_KW)
                        ->setCashRegisterNumber($enumerator->getCashRegisterNumber())
                        ->setIsSettlement(true)
                        ->setTransactionDate(new \DateTime());

                    $cashDetails = new CashRegisterDetail();
                    $cashDetails
                        ->setCashRegister($cashRegister)
                        ->setQuantity(1)
                        ->setBrutto($form->get('amount')->getData())
                        ->setParam($form->get('param')->getData());
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($enumerator);
                    $manager->persist($cashRegister);
                    $manager->persist($cashDetails);
                    $manager->flush();

                    $transactions = json_decode($form->get('transactions')->getData(), true);
                    if (count($transactions) == 0) {
                        return $this->redirectToRoute('route_transaction_settlement');
                    }

                    $transRepo = $this->getDoctrine()->getRepository(Transaction::class);
                    $transRepo->settleTransactions($transactions, $cashRegister, $this->getUser());

                    $this->addFlash('print', $cashRegister->getId());

                    return $this->redirectToRoute('route_transaction_settlement');
                }
            }
        }
        return $this->render('@App/transaction/settlement.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * AJAX - pobiera transakcje do rozliczenia
     * @Route(
     *     "/api/rozliczenia",
     *     name="route_transaction_settlement_api_get"
     * )
     */
    public function getSettlementAction(Request $request)
    {
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $params = [
            'driverId' => $request->request->get("driverId"),
            'ownCards' => $request->request->get("ownCards") == 'true' ? true : false,
            'bankTransaction' => $request->request->get("bankTransaction") == 'true' ? true : false,
            'sort' => $request->request->get('sort')
        ];

        return $this->json(array(
            'data' => $transactionRepo->findTransactionForSettlement($params)
        ));
    }

    /**
     * Nierozliczone transakcje
     * @Route(
     *     "/nierozliczone-transakcje",
     *     name="route_transaction_unassigned"
     * )
     */
    public function unassignedAction()
    {
        $form = $this->createForm(UnassignedDriverForm::class);

        return $this->render('@App/transaction/unassigned.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * API - pobierz nieprzypisane transakcje
     * @Route(
     *     "/api/unassigned",
     *     name="route_unassigned_api_get"
     * )
     */
    public function getUnassignedAction()
    {
        $response = $this->getDoctrine()->getRepository(Transaction::class)
            ->getUnassignedTransactionList();

        return $this->json(array(
            "data" => $response
        ));
    }

    /**
     * API - Przypisz kierowcę do transakcji
     * @Route(
     *     "/api/assign",
     *     name="route_unassigned_api_assign"
     * )
     */
    public function assignTransactionAction(Request $request)
    {
        $requestData = json_decode($request->request->get('formData'));
        $driver = $this->getDoctrine()->getRepository(Driver::class)
            ->find($requestData->driverId);
        $transactions = $requestData->items;

        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $manager = $this->getDoctrine()->getManager();
        foreach ($transactions as $transaction) {
            /**
             * @var Transaction $tran
             */
            $tran = $repo->find($transaction);
            $tran->setDriverId($driver)
                ->setOriginalLicenseNumber($driver->getLicenseNumber())
                ->setOriginalTid($driver->getTerminalTid())
            ;
            $manager->persist($tran);
        }
        $manager->flush();

        return $this->json(array(
            'data' => $requestData->items
        ));
    }

    /**
     * Import transakcji
     * @Route(
     *     "/import-danych",
     *     name="route_transaction_import_csv"
     * )
     * TODO: Dodać sprawdzenie czy wysyłany jest poprawny plik
     */
    public function importAction(Request $request, ImportTransaction $import, KernelInterface $kernel)
    {
        set_time_limit(0);
        $form = $this->createForm(TransactionFileForm::class);

        if($request->isMethod('POST'))
        {
            $watch = new Stopwatch();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                try{
                    $import->importTransactionFromFile(
                        $form->get('filename')->getData(),
                        $this->getUser(),
                        $kernel);
                    $this->addFlash('success', 'Zaimportowano poprawnie!');

                    return $this->redirectToRoute('route_transaction_import_csv');
                }catch(\Exception $e){

                }
            }
        }

        return $this->render('@App/transaction/import_csv.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Eksport CSV
     * @Route(
     *     "/export/csv",
     *     name="route_transaction_export_csv"
     * )
     */
    public function exportCSV(Request $request)
    {
        $transactionRepo = $this->getDoctrine()->getRepository(Transaction::class);
        $csv = Writer::createFromPath('php://temp', 'r+');
        try {
            $csv->setDelimiter(";");
            $csv->insertOne(array(
                'Numer transakcji','Data transakcji', 'Kwota', 'Kwota po potraceniu', '','','Typ transakcji', 'Nr Licencji', 'Kierowca',
                'Nr karty', 'Typ Karty', 'Rozliczona', 'Prowizja', 'Nr umowy firmy', 'Nazwa firmy',
            ));
        } catch (CannotInsertRecord $e) {

        } catch (Exception $e) {

        }

        $params = array(
            "dateFrom" => $request->request->get("dateFrom"),
            "dateTo" => $request->request->get("dateTo"),
            "licenseNumber" => $request->request->get("licenseNumber"),
            "clientNumber" => $request->request->get("clientNumber"),
            "cardNumber" => $request->request->get("cardNumber")
        );
        $csv->insertAll($transactionRepo->findTransactions($params, true));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv;charset=UTF-8');
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'transakcje.csv'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Description', 'File Transfer');
        $response->setContent($csv);

        return $response;
    }

}

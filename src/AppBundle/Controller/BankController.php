<?php

namespace AppBundle\Controller;

use AppBundle\Form\BankFormType;
use AppBundle\Service\BankService;
use Exception;
use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterDetail;
use AppBundle\Entity\Dictionary\DictionaryParam;
use AppBundle\Entity\Enumerator;
use AppBundle\Entity\Settlement;
use AppBundle\Entity\ApiTaxi360\Transaction;

/**
 * @Route(
 *     "/przelewy"
 * )
 */
class BankController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_bank"
     * )
     */
    public function indexAction()
    {
        $form = $this->createForm(BankFormType::class);

        return $this->render('@App/bank/index.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Bool $generateTransactions
     * @Route(
     *     "/ajax/calculate",
     *     name="route_bank_calculate"
     * )
     */
    public function calculateAction(Request $request, BankService $bank, $generateTransactions = false)
    {
        if($generateTransactions) $formData = $request;
        else $formData = $request->request->get('formData');

        $sum = $bank->calculate($formData, $generateTransactions);

        if($generateTransactions){
            return $sum;
        }
        else{
            return $this->json([
                'response' => $sum
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route(
     *     "/generate-file",
     *     name="route_bank_generate_file"
     * )
     */
    public function generateFile(Request $request, BankService $bank)
    {
        $csv = Writer::createFromPath('php://temp', 'r+');
        try {
            $csv->setDelimiter(";");
            $csv->insertOne(array(
                'Numer transakcji','Data transakcji', 'Kwota'
            ));
        } catch (CannotInsertRecord $e) {

        } catch (Exception $e) {

        }

        $transactions = $this->calculateAction($request, $bank, true);
        $transactions = $transactions['transactions'];

        $csv->insertAll($transactions);

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

    /**
     * @Route(
     *     "/ajax/settle",
     *     name="route_bank_settle"
     * )
     */
    public function settleAction(Request $request, BankService $bank)
    {
        $formData = $request->request->get('formData');
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $transactionDate = new \DateTime();
        $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)->getEnumerator(Enumerator::TYPE_KW, $this->getUser());
        $driverId = $bank->getDriverIdFromForm($formData);
        $driver = $this->getDoctrine()->getRepository(Driver::class)->find($driverId);
        $param = $this->getDoctrine()->getRepository(DictionaryParam::class)->find(74);
        $sum = $bank->calculate($formData, false);
        $amountForSettlement = $sum['forSettlement']['amount'];

        //add new KW
        $cashRegister = new CashRegister();
        $cashRegister
            ->setTransactionDate($transactionDate)
            ->setTransactionType('kw')
            ->setDriver($driver)
            ->setUser($user)
            ->setTitle('Rozliczenie przelewów')
            ->setCashRegisterNumber($enumerator->getCashRegisterNumber());
        $details = new CashRegisterDetail();
        $details
            ->setCashRegister($cashRegister)
            ->setParam($param)
            ->setQuantity(1)
            ->setBrutto($amountForSettlement);
        $manager->persist($cashRegister);
        $manager->persist($details);
        $manager->persist($enumerator);
        $manager->flush();

        $bank->updateSettlements($formData);

        //odliczyć koszty przelewu - pobrać kwotę kosztu przelewu i dodać ją jako nowa transakcja
        $transferCost = $bank->getTransferCost();
        $transaction = new Transaction();
        $transaction
            ->setDriverId($driver)
            ->setUser($user)
            ->setTransactionDate($transactionDate)
            ->setTransactionStatus('ACCEPTED')
            ->setTransactionStatusCode('000')
            ->setTotalAmount($transferCost)
            ->setDriverAmount($transferCost)
            ->setPrice($transferCost)
            ->setManual(1)
            ->setCardAliasUsed('NO')
            ->setCorpoAliasUsed('NO')
            ->setAuthDate($transactionDate)
            ->setUpdateDate($transactionDate);
        $manager->persist($transaction);
        $manager->flush();

        $settlement = new Settlement();
        $settlement
            ->setTransaction($transaction)
            ->setUser($user)
            ->setTotalAmount($transferCost)
            ->setPercentage(0);
        $manager->persist($settlement);
        $manager->flush();

        return $this->json([
            'insertedKw' => $cashRegister->getId()
        ]);
    }
}

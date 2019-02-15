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
        $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)
            ->getEnumerator(Enumerator::TYPE_KW, $this->getUser());
        //pobrać id drivera z form
        $driver = $this->getDoctrine()->getRepository(Driver::class)->find(1);
        //zamiast dictionary param wyświetla się zwykły param...
        $param = $this->getDoctrine()->getRepository(DictionaryParam::class)->find(74);
        $cashRegister = new CashRegister();
        $cashRegister
            ->setTransactionDate(new \DateTime())
            ->setTransactionType('kw')
            ->setDriver($driver)
            ->setUser($this->getUser())
            ->setTitle('Rozliczenie przelewów')
            ->setCashRegisterNumber($enumerator->getCashRegisterNumber());
        $details = new CashRegisterDetail();
        $details
            ->setCashRegister($cashRegister)
            ->setParam($param)
            ->setQuantity(1)
            //pobrać kwotę z form
            ->setBrutto(12);
        $manager->persist($cashRegister);
        $manager->persist($details);
        $manager->persist($enumerator);
        $manager->flush();
        //$bank->updateSettlements($formData);

        //odliczyć koszty przelewu - pobrać kwotę kosztu przelewu i dodać ją jako nowa transakcja
        return $this->json([
            'insertedKw' => $cashRegister->getId(),
        ]);
    }
}

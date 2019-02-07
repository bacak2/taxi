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
}

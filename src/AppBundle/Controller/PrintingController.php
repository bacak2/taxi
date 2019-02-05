<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterReport;
use AppBundle\Entity\Invoice\Invoice;
use AppBundle\Entity\Settlement;
use AppBundle\Repository\SettlementRepository;
use Mpdf\MpdfException;
use Mpdf\Mpdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(
 *     "/wydruk"
 * )
 */
class PrintingController extends Controller
{
    /**
     * @var \Mpdf\Mpdf $pdf
     */
    private $pdf;

    public function __construct()
    {
        $this->pdf = new Mpdf();
    }

    /**
     * KW - standard
     * @Route(
     *     "/standard-kw/{id}",
     *     name="print_standard_kw"
     * )
     */
    public function printStandardKW($id)
    {
        $repo = $this->getDoctrine()->getRepository(CashRegister::class);
        $document = $repo->printKWStandard($id);
        if($document == NULL){
            $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }

        $this->pdf->WriteHTML(
            $this->renderView('@App/print/kw/print_kw_standard.html.twig', array(
            'document' => $document,
            'logo' => $this->getLogo()
        )));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     * KW - rozliczenie transakcji
     * @Route(
     *     "/bezgotowka-kw/{id}",
     *     name="print_settlement_kw"
     * )
     * @throws \Doctrine\DBAL\DBALException
     */
    public function printSettlementKW($id)
    {
        $repo = $this->getDoctrine()->getRepository(Settlement::class);
        /**
         * @var Settlement $document
         */
        $document = $repo->printSettledTransaction($id);
        if($document == NULL){
            return $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }

        $this->pdf->WriteHTML(
            $this->renderView('@App/print/kw/print_kw_settlement.html.twig', array(
            'document' => $document,
            'logo' => $this->getLogo()
        )));

        $this->pdf->Output();

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     * KP - standard
     * @Route(
     *     "/standard-kp/{id}",
     *     name="print_standard_kp"
     * )
     */
    public function printStandardKP($id)
    {
        $repo = $this->getDoctrine()->getRepository(CashRegister::class);
        $document = $repo->printKPStandard($id);
        if($document == NULL)
        {
            return $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }
        $this->pdf->WriteHTML($this->renderView('@App/print/kp/print_kp_standard.html.twig', array(
            'document' => $document,
            'logo' => $this->getLogo()
        )));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     * KP - magazyn
     * @Route(
     *     "/magazyn-kp/{id}",
     *     name="print_warehouse_kp"
     * )
     */
    public function printWerahouseKP($id)
    {
        $repo = $this->getDoctrine()->getRepository(CashRegister::class);
        $document = $repo->printKPWarehouseData($id);
        if($document == NULL)
        {
            $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }

        $this->pdf->WriteHTML($this->renderView('@App/print/kp/print_kp_warehouse.html.twig', array(
            'document' => $document,
            'logo' => $this->getLogo()
        )));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     *
     * @Route(
     *     "/kasa-raport/{id}",
     *     name="print_cash_register_report"
     * )
     */
    public function printCashRegisterReport($id)
    {
        $repo = $this->getDoctrine()->getRepository(CashRegisterReport::class);
        $document = $repo->findPrintReportData($id);

        if($document == NULL)
        {
            $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }

        $this->pdf->WriteHTML($this->renderView('@App/print/report/cash_register_report.html.twig', array(
            'document' => $document,
            'logo' => $this->getLogo()
        )));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     *
     * @Route(
     *     "/lista-raportow",
     *     name="print_cash_register_report_list"
     * )
     */
    public function printCashRegisterReportList(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(CashRegisterReport::class);
        $params = array(
            "dateFrom" => $request->request->get("dateFrom"),
            "dateTo" => $request->request->get("dateTo")
        );
        $reports = $repo->findReports($params);

        $this->pdf->WriteHTML($this->renderView('@App/print/report/cash_register_report_list.html.twig', array(
            'reports' => $reports,
            'logo' => $this->getLogo()
        )));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     * INVOICE - client
     * @Route(
     *     "/faktura/firma/{invoiceId}",
     *     name="print_invoice_for_client"
     * )
     */
    public function printClientInvoice($invoiceId)
    {
        $repo = $this->getDoctrine()->getRepository(Invoice::class);
        $document = $repo->find($invoiceId);
//        dump($document);
//        die;
        if($document == NULL)
        {
            return $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }
        $path = $this->get('kernel')->getRootDir(). '/../web/assets/transparent_logo.png';

        $transactions = $this->getDoctrine()->getRepository(Transaction::class)
            ->getTransactionForInvoiceAttachment($invoiceId);
        $currency = explode('.',
            number_format( $transactions[80]['totalAmount']*$transactions['vat']+$transactions[80]['totalAmount'],2));
        $obj = new \Numbers_Words_Locale_pl();
        $word = $obj->toCurrencyWords('PLN', $currency[0], $currency[1], true);


        $this->pdf->setHTMLFooter($this->renderView('@App/print/clientInvoice/footer.html.twig'));
        $this->pdf->WriteHTML($this->renderView('@App/print/clientInvoice/invoice.html.twig', array(
            'document' => $transactions,
            'invoice' => $document,
            'logo' => $path,
            'word' => $word
        )));
        $this->pdf->AddPage();
        $this->pdf->WriteHTML($this->renderView(
            '@App/print/clientInvoice/attachment.html.twig', [
                'document' => $transactions,
                'logo' => $path
            ]
        ));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    /**
     * INVOICE - driver
     * @Route(
     *     "/faktura/kierowca/{invoiceId}",
     *     name="print_invoice_for_driver"
     * )
     */
    public function printDriverInvoice($invoiceId)
    {
        $repo = $this->getDoctrine()->getRepository(Invoice::class);
        $document = $repo->find($invoiceId);
        if($document == NULL)
        {
            return $this->createNotFoundException("Nie znaleziono takiego dokumentu w bazie danych!");
        }
        $path = $this->get('kernel')->getRootDir(). '/../web/assets/transparent_logo.png';

        $transactions = $this->getDoctrine()->getRepository(Transaction::class)
            ->getTransactionForInvoiceAttachment($invoiceId);
        $currency = explode('.', number_format( $transactions[80]['totalAmount']*$transactions['vat']+$transactions[80]['totalAmount'],2));
        $obj = new \Numbers_Words_Locale_pl();
        $word = $obj->toCurrencyWords('PLN', $currency[0], $currency[1], true);


        $this->pdf->setHTMLFooter($this->renderView('@App/print/clientInvoice/footer.html.twig'));
        $this->pdf->WriteHTML($this->renderView('@App/print/clientInvoice/invoice.html.twig', array(
            'document' => $transactions,
            'logo' => $path,
            'word' => $word
        )));
        $this->pdf->AddPage();
        $this->pdf->WriteHTML($this->renderView(
            '@App/print/clientInvoice/attachment.html.twig', [
                'document' => $transactions,
                'logo' => $path
            ]
        ));

        try{
            $this->pdf->Output();
        }catch(MpdfException $e){

        }

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }

    private function getLogo()
    {
        return $this->get('kernel')->getRootDir(). '/../web/assets/logo_g.png';
    }
}

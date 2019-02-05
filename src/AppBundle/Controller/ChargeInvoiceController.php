<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice\Invoice;
use AppBundle\Form\Invoice\ChargesForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/faktury-oplaty-skladki"
 * )
 */
class ChargeInvoiceController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_charge_invoice"
     * )
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ChargesForm::class);

        return $this->render('@App/invoice/charges/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     *     "/wystaw-fakture",
     *     name="route_charge_invoice_print"
     * )
     */
    public function createAndPrintInvoiceAction()
    {
        return $this->json([
            'status' => 'success'
        ]);
    }

    /**
     * @Route(
     *     "/faktury",
     *     name="route_charge_invoice_getDriverInvoice"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDriverInvoice(Request $request)
    {
        $id = $request->request->get('driverId');

        $invoiceRepo = $this->getDoctrine()->getRepository(Invoice::class);
        $invoices = $invoiceRepo->findInvoiceByDriverId($id);

        if($invoices == null)
        {
            $invoices = [
                [
                    'id' => 15,
                    'invoice_year' => '2018',
                    'invoice_month' => '03',
                    'invoice_number' => 'FV-01',
                    'document_type' => 'Sprzedaż',
                    'invoice_type' => 'Abonament',
                    'amount_brutto' => 560.00
                ],
                [
                    'id' => 32,
                    'invoice_year' => '2018',
                    'invoice_month' => '03',
                    'invoice_number' => 'FV-02',
                    'document_type' => 'Sprzedaż',
                    'invoice_type' => 'KPMagazyn',
                    'amount_brutto' => 40.00
                ]
            ];
            $details[15][] = [
                        'invoice_title' => 'Opłata za telefon',
                        'vat' => '23%',
                        'detail_amount' => 40.00
            ];
            $details[15][] = [
                        'invoice_title' => 'Opłata za telefon',
                        'vat' => '23%',
                        'detail_amount' => 50.0
                ];
            $details[32] =[
                [
                    'invoice_title' => 'Opłata za telefon',
                    'vat' => '23%',
                    'detail_amount' => 32.00
                ]
            ];
        }

        return $this->json([
            'data' => $invoices,
            'details' => $details
        ]);
    }

}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\Invoice\Invoice;
use AppBundle\Form\Invoice\EditClientInvoice;
use AppBundle\Service\InvoiceService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/faktury/firmy"
 * )
 */
class InvoiceClientController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_invoice_client"
     * )
     */
    public function indexAction(InvoiceService $service)
    {
//        $clients = ["52"];
//        $service->createInvoiceForClient($clients,'03','2018');

        return $this->render('@App/invoice/client/index.html.twig', array());
    }

    /**
     * AJAX
     * @Route(
     *     "/get-transactions",
     *     name="route_invoice_client_get_transactions"
     * )
     */
    public function getTransactionsForInvoice(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $data = $repo->getTransactionForInvoice([
            'year' => $request->request->get('txtYear'),
            'month' => $request->request->get('txtMonth')
        ]);


        return $this->json(array(
            'data' => $data,
        ));
    }

    /**
     * AJAX
     * @Route(
     *     "/get-invoice",
     *     name="route_invoice_client_get"
     * )
     */
    public function getInvoiceForDate(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $data = $repo->getInvoice([
            'year' => $request->request->get('txtYear'),
            'month' => $request->request->get('txtMonth')
        ]);

        return $this->json(array(
            'data' => $data
        ));
    }

    /**
     * AJAX
     * @Route(
     *     "/create-invoice",
     *     name="route_invoice_client_create"
     * )
     */
    public function createInvoice(Request $request, InvoiceService $service)
    {
        $clients = json_decode($request->request->get('clients'), true);
        $year = $request->request->get('year');
        $month = $request->request->get('month');


        $resp = $service->createInvoiceForClient($clients, $month, $year);

        return $this->json([
            $clients, $resp
        ]);
    }

    /**
     * @Route(
     *     "/edycja/{id}",
     *     name="route_invoice_edit"
     * )
     */
    public function editInvoiceAction($id)
    {
        $invoice = $this->getDoctrine()->getRepository(Invoice::class)
            ->find($id);

        $form = $this->createForm(EditClientInvoice::class, $invoice);

        return $this->render(
            '@App/invoice/client/edit.html.twig',
            [
                'invoiceNumber' => $invoice->getInvoiceNumber(),
                'form' => $form->createView()
            ]
        );
    }
}

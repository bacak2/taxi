<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ApiTaxi360\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\InvoiceService;

/**
 * @Route(
 *     "/faktury/kierowcy"
 * )
 */
class InvoiceDriverController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="route_invoice_driver"
     * )
     */
    public function indexAction(Request $request, InvoiceService $service)
    {
        if($request->isMethod('POST'))
        {
            $drivers = json_decode($request->request->get('drivers'));
            $year = $request->request->get('year');
            $month = $request->request->get('month');
            $service->createInvoiceForDriver($drivers, $month, $year);
        }
        return $this->render('@App/invoice/driver/index.html.twig', array());
    }

    /**
     * @Route(
     *     "/create-invoices-diver",
     *     name="route_all_invoices_driver"
     * )
     */
    public function createAllInvoicesAction(Request $request, InvoiceService $service)
    {
        if($request->isMethod('POST'))
        {
            $drivers = json_decode($request->request->get('drivers'));
            $year = $request->request->get('year');
            $month = $request->request->get('month');
            $service->createInvoiceForDriver($drivers, $month, $year);
        }
        return $this->render('@App/invoice/driver/index.html.twig', array());
    }

    /**
     * @Route(
     *     "/get-invoice",
     *     name="route_invoice_driver_get"
     * )
     */
    public function getInvoiceForDate(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $data = $repo->getDriverInvoice([
            'year' => $request->request->get('txtYear'),
            'month' => $request->request->get('txtMonth')
        ]);

        return $this->json(array(
            'data' => $data
        ));
    }

    /**
     * @Route(
     *     "/get-transactions",
     *     name="route_invoice_driver_get_transactions"
     * )
     */
    public function getTransactionsForInvoice(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $data = $repo->getTransactionForDriverInvoice([
            'year' => $request->request->get('txtYear'),
            'month' => $request->request->get('txtMonth')
        ]);


        return $this->json(array(
            'data' => $data,
        ));
    }
}

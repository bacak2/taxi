<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Blockade;
use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterReport;
use AppBundle\Repository\CashRegisterRepository;
use AppBundle\Service\EmailManager;
use AppBundle\Service\InvoiceService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends Controller
{
    /**
     * @Route(
     *     "/",
     *     name="app"
     * )
     */
    public function indexAction(InvoiceService $invoice, EmailManager $email)
    {
//        $repo = $this->getDoctrine()->getRepository(CashRegister::class);
//        $rows = $repo->getCashRegisterList(new \DateTime('2018-05-14'),
//            CashRegisterReport::DAILY_REPORT, $this->getUser());
//        dump($rows);

//        $repo = $this->getDoctrine()->getRepository(Blockade::class)
//            ->findTransactionsForDriver(4);
//        dump($repo);
//        die;
//        $clients = ['383','620','317'];
//        $invoice->createInvoiceForClient($clients, '05', '2018');
        $year = '20181008';
        $hour = '102805';

//        $date = new \DateTime($year . ' ' . $hour);
//        dump($date);
        return $this->render('@App/default/index.html.twig',[]);
//        return $this->render('@App/email/clientInvoice.html.twig', []);
    }
}
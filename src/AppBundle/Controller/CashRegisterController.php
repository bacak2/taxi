<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CashRegister\CashRegister;
use AppBundle\Entity\CashRegister\CashRegisterReport;
use AppBundle\Entity\Enumerator;
use AppBundle\Form\CashRegister\CashRegisterReportForm;
use AppBundle\Service\EnumManager;
use Doctrine\Common\Annotations\Annotation\Enum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CashRegisterController extends Controller
{
    /**
     * @Route(
     *     "/raport-kasowy",
     *     name="route_cash_register_report"
     * )
     */
    public function reportAction(Request $request)
    {
        $reportForm = new CashRegisterReport();
        $reportForm->setReportForDate(new \DateTime());
        $form = $this->createForm(CashRegisterReportForm::class, $reportForm);

        if($request->isMethod('POST') && !$request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if($reportForm->getKwCount() == 0 && $reportForm->getKpAmount() == 0)
                {
                    return $this->redirectToRoute('route_cash_register_report');
                }

                $manager = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository(CashRegisterReport::class);

                $report = $repo->findReportByTypeAndDate(
                    $reportForm->getReportForDate(), $reportForm->getReportType(), $this->getUser());

                /**
                 * Jeżeli raport już istnieje w bazie danych do omiń tworzenie nowego numeru
                 */
                if ($report == null) {
                    $report = new CashRegisterReport();
                    $enumerator = $this->getDoctrine()->getRepository(Enumerator::class)
                        ->getEnumerator(Enumerator::TYPE_RAPORT_KASOWY, $this->getUser());
                    $manager->persist($enumerator);
                    $report->setReportNumber($enumerator->getCashRegisterReportNumber());
                }

                $report
                    ->setReportType($reportForm->getReportType())
                    ->setReportForDate($reportForm->getReportForDate())
                    ->setKpCount($reportForm->getKpCount())
                    ->setKpAmount($reportForm->getKpAmount())
                    ->setKwCount($reportForm->getKwCount())
                    ->setKwAmount($reportForm->getKwAmount())
                    ->setAmount($reportForm->getAmount())
                    ->setPrevAmount($reportForm->getPrevAmount())
                    ->setChangeAmount($reportForm->getChangeAmount())
                    ->setUser($this->getUser());
                $settlements = implode(",", json_decode($form->get('settlements')->getData(), true));

                $manager->persist($report);
                $manager->flush();

                $repo->addReportIdToSettlements(
                    $report->getId(),$reportForm->getReportType(),$settlements);

                $this->addFlash('print', $report->getId());
                return $this->redirectToRoute('route_cash_register_report');
            }
        }

        return $this->render('@App/cashregister/report.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     *     "/raport/kpkw",
     *     name="route_api_getKwKpList"
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getReportList(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(CashRegister::class);
        
        $date = new \DateTime($request->request->get('date'));
        $period = $request->request->get('period');

        return $this->json($repo->getCashRegisterList($date, $period, $this->getUser()));
    }

    /**
     * @Route(
     *     "/lista-raportow",
     *     name="route_cash_register_list"
     * )
     */
    public function reportListAction(Request $request)
    {
        return $this->render('@App/cashregister/report_list.html.twig', array());
    }

    /**
     * AJAX - Raporty
     * @Route(
     *     "/lista-raportow/api/get",
     *     name="route_cash_register_api_get"
     * )
     */
    public function getReportsAction(Request $request)
    {
        $reportRepo = $this->getDoctrine()->getRepository(CashRegisterReport::class);
        $params = array(
            "dateFrom" => $request->request->get("dateFrom"),
            "dateTo" => $request->request->get("dateTo")
        );

        return $this->json(array(
            'data' => $reportRepo->findReports($params, false)
        ));
    }

}

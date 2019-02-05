<?php

namespace AppBundle\Service;

use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

class PrintService
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * PrintService constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }


    public function printDocument($path)
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($this->twig->render('@App/print/kw/print_kw_standard.html.twig', array(
            'driver' => 'Jan Kowalski',
            'logo' => $path
        )));

        $mpdf->Output();

        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');

        return $response;
    }
}
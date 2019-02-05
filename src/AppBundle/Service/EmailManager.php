<?php

namespace AppBundle\Service;


class EmailManager
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEmail($to = ['pg.gno@o2.pl'])
    {
        $message = (new \Swift_Message())
            ->setSubject('RadioTaxi919 - Faktura')
            ->setFrom(['fv@kasa.radiotaxi919.pl' => 'RadioTaxi919'])
            ->setReplyTo(['biuro@radiotaxi919.pl'])
            ->setTo($to)
            ->setBody($this->twig->render('@App/email/clientInvoice.html.twig'),
                'text/html')
        ;

        $this->mailer->send($message);
    }
}
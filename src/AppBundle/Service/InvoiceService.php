<?php

namespace AppBundle\Service;


use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\Enumerator;
use AppBundle\Entity\Invoice\Invoice;
use AppBundle\Entity\Invoice\InvoiceDetail;
use AppBundle\Entity\Invoice\InvoiceFormat;
use AppBundle\Entity\Invoice\InvoiceType;
use AppBundle\Entity\Invoice\PaymentMethod;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class InvoiceService
{
    /**
     * @var RegistryInterface $doctrine
     */
    private $doctrine;

    /**
     * @var TokenStorageInterface $storage
     */
    private $storage;

    /**
     * InvoiceService constructor.
     * @param RegistryInterface $doctrine
     * @param TokenStorageInterface $storage
     */
    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $storage)
    {
        $this->doctrine = $doctrine;
        $this->storage = $storage;
    }

    public function createInvoiceForClient($clients, $month, $year)
    {
        $manager = $this->doctrine->getManager();
        $transRepo = $this->doctrine->getRepository(Transaction::class);

        $invoiceType = $this->doctrine->getRepository(InvoiceType::class)
            ->findOneBy([
                'slug' => 'bezgotowka'
            ]);
        $invoiceFormat = $this->doctrine->getRepository(InvoiceFormat::class)
            ->findOneBy([
                'format' => 'Faktura'
            ]);
        $paymentMethod = $this->doctrine->getRepository(PaymentMethod::class)
            ->findOneBy([
                'slug' => 'przelew'
            ]);

        foreach ($clients as $client)
        {
            $transactions = $transRepo->getTransactionForInvoiceDetails([
                'year' => $year,
                'month' => $month,
                'clientId' => $client
            ]);
            if ($transactions == null)
            {
                continue;
            }
            $invoiceData = [];
            $transactionToUpdate = [];
            foreach ($transactions as $transaction)
            {
                $invoiceData['clientId'] = $transaction['clientId'];
                $invoiceData['clientName'] = $transaction['clientName'];
                $invoiceData['agreementNumber'] = $transaction['agreementNumber'];
                $invoiceData['clientDiscount'] = $transaction['clientDiscount'];
                $invoiceData['paymentDays'] = $transaction['paymentDays'];
                $invoiceData['vat'] = $transaction['vat'];

                if(!isset($invoiceData['totalNetto']))
                {
                    $invoiceData['totalNetto'] = 0;
                }
                $invoiceData['totalNetto'] += $transaction['totalNetto'];

                if(!isset($invoiceData['totalBrutto']))
                {
                    $invoiceData['totalBrutto'] = 0;
                }
                $invoiceData['totalBrutto'] += $transaction['totalBrutto'];

                if(isset($invoiceData['minDate']))
                {
                    $invoiceData['minDate'] = $transaction['transactionDate'] > $invoiceData['minDate']
                        ? $invoiceData['minDate'] : $transaction['transactionDate'];
                }else{
                    $invoiceData['minDate'] = $transaction['transactionDate'];
                }
                if(isset($invoiceData['maxDate']))
                {
                    $invoiceData['maxDate'] = $transaction['transactionDate'] < $invoiceData['maxDate']
                        ? $invoiceData['maxDate'] : $transaction['transactionDate'];
                }else{
                    $invoiceData['maxDate'] = $transaction['transactionDate'];
                }

                $item['transactionId'] = $transaction['transactionId'];
                $item['transactionDate'] = $transaction['transactionDate'];
                $item['totalNetto'] = $transaction['totalNetto'];
                $item['vat'] = $transaction['vat'];
                $item['totalBrutto'] = $transaction['totalBrutto'];

                $invoiceData['transactions'][] = $item;
                $transactionToUpdate[] = $transaction['transactionId'];
            }

            /**
             * @var Enumerator $enumerator
             */
            $enumerator = $this->doctrine->getRepository(Enumerator::class)
                ->getEnumerator(Enumerator::TYPE_FV);

            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->setLp(1)
                ->setTitle(sprintf('Usługi taksówkowe w okresie od %s do %s',
                    $invoiceData['minDate'],$invoiceData['maxDate']))
                ->setQuantity(1)
                ->setVat($invoiceData['vat'])
                ->setAmountNetto($invoiceData['totalNetto'])
                ->setAmountBrutto($invoiceData['totalBrutto'])
            ;

            $date = new \DateTime();
            $paymentDate = (new \DateTime())->add(new \DateInterval('P'.$invoiceData['paymentDays'].'D'));

            $invoice = new Invoice();
            $invoice->setVat($invoiceData['vat'])
                ->setAmountNetto($invoiceData['totalNetto'])
                ->setAmountBrutto($invoiceData['totalBrutto'])
                ->setCreateDate($date)
                ->setTransactionType(Invoice::TYPE_SALE)
                ->setInvoiceType($invoiceType)
                ->setSeller(0)
                ->setSellerType(Invoice::TYPE_CLIENT)
                ->setBuyer($client)
                ->setBuyerType(Invoice::TYPE_CLIENT)
                ->setDiscount(0)
                ->setInvoiceFormat($invoiceFormat)
                ->setInvoiceMonth($date->format('m'))
                ->setInvoiceYear($date->format('Y'))
                ->setInvoiceNumber($enumerator->getInvoiceNumber())
                ->setPaymentDate($paymentDate)
                ->setPaymentMethod($paymentMethod)
                ->setUser($this->storage->getToken()->getUser())
                ->setInvoiceType($this->doctrine->getRepository(InvoiceType::class)->find(1))
                ;
            $invoice->addInvoiceDetail($invoiceDetail);

            $manager->persist($enumerator);
            $manager->persist($invoice);
            $manager->flush();

            $transRepo->updateTransactionByInvoice($transactionToUpdate, $invoice);
        }
    }

    public function createInvoiceForDriver($drivers, $month, $year)
    {
        $manager = $this->doctrine->getManager();
        $transRepo = $this->doctrine->getRepository(Transaction::class);

        $invoiceType = $this->doctrine->getRepository(InvoiceType::class)
            ->findOneBy([
                'slug' => 'bezgotowka'
            ]);
        $invoiceFormat = $this->doctrine->getRepository(InvoiceFormat::class)
            ->findOneBy([
                'format' => 'Faktura'
            ]);
        $paymentMethod = $this->doctrine->getRepository(PaymentMethod::class)
            ->findOneBy([
                'slug' => 'przelew'
            ]);

        foreach ($drivers as $driver)
        {
            $transactions = $transRepo->getTransactionForDriverInvoiceDetails([
                'year' => $year,
                'month' => $month,
                'driverId' => $driver
            ]);
            if ($transactions == null) continue;
            $invoiceData = [];
            $transactionToUpdate = [];

            foreach ($transactions as $transaction)
            {
                $invoiceData['clientId'] = $transaction['driverId'];
                $invoiceData['clientName'] = $transaction['name'];
                $invoiceData['agreementNumber'] = 10;
                $invoiceData['clientDiscount'] = 10;
                $invoiceData['paymentDays'] = 10;
                $invoiceData['vat'] = $transaction['vat'];

                if(!isset($invoiceData['totalNetto']))
                {
                    $invoiceData['totalNetto'] = 0;
                }
                $invoiceData['totalNetto'] += $transaction['totalNetto'];

                if(!isset($invoiceData['totalBrutto']))
                {
                    $invoiceData['totalBrutto'] = 0;
                }
                $invoiceData['totalBrutto'] += $transaction['totalBrutto'];

                if(isset($invoiceData['minDate']))
                {
                    $invoiceData['minDate'] = $transaction['transactionDate'] > $invoiceData['minDate']
                        ? $invoiceData['minDate'] : $transaction['transactionDate'];
                }else{
                    $invoiceData['minDate'] = $transaction['transactionDate'];
                }
                if(isset($invoiceData['maxDate']))
                {
                    $invoiceData['maxDate'] = $transaction['transactionDate'] < $invoiceData['maxDate']
                        ? $invoiceData['maxDate'] : $transaction['transactionDate'];
                }else{
                    $invoiceData['maxDate'] = $transaction['transactionDate'];
                }

                $item['transactionId'] = $transaction['transactionId'];
                $item['transactionDate'] = $transaction['transactionDate'];
                $item['totalNetto'] = $transaction['totalNetto'];
                $item['vat'] = $transaction['vat'];
                $item['totalButto'] = $transaction['totalBrutto'];

                $invoiceData['transactions'][] = $item;
                $transactionToUpdate[] = $transaction['transactionId'];
            }

            /**
             * @var Enumerator $enumerator
             */
            $enumerator = $this->doctrine->getRepository(Enumerator::class)
                ->getEnumerator(Enumerator::TYPE_FV);

            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->setLp(1)
                ->setTitle(sprintf('Usługi taksówkowe w okresie od %s do %s',
                    $invoiceData['minDate'],$invoiceData['maxDate']))
                ->setQuantity(1)
                ->setVat($invoiceData['vat'])
                ->setAmountNetto($invoiceData['totalNetto'])
                ->setAmountBrutto($invoiceData['totalBrutto'])
            ;

            $date = new \DateTime();
            $paymentDate = (new \DateTime())->add(new \DateInterval('P'.$invoiceData['paymentDays'].'D'));

            $invoice = new Invoice();
            $invoice->setVat($invoiceData['vat'])
                ->setAmountNetto($invoiceData['totalNetto'])
                ->setAmountBrutto($invoiceData['totalBrutto'])
                ->setCreateDate($date)
                ->setTransactionType(Invoice::TYPE_SALE)
                ->setInvoiceType($invoiceType)
                ->setSeller(0)
                ->setSellerType(Invoice::TYPE_DRIVER)
                ->setBuyer($driver)
                ->setBuyerType(Invoice::TYPE_DRIVER)
                ->setDiscount(0)
                ->setInvoiceFormat($invoiceFormat)
                ->setInvoiceMonth($date->format('m'))
                ->setInvoiceYear($date->format('Y'))
                ->setInvoiceNumber($enumerator->getInvoiceNumber())
                ->setPaymentDate($paymentDate)
                ->setPaymentMethod($paymentMethod)
                ->setUser($this->storage->getToken()->getUser())
                ->setInvoiceType($this->doctrine->getRepository(InvoiceType::class)->find(2))
                ;
            $invoice->addInvoiceDetail($invoiceDetail);

            $manager->persist($enumerator);
            $manager->persist($invoice);
            $manager->flush();

            $transRepo->updateTransactionByDriverInvoice($transactionToUpdate, $invoice);
        }
    }


}
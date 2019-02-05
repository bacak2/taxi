<?php

namespace AppBundle\Service;

use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\Settlement;
use AppBundle\Entity\User;
use AppBundle\Vendor\Xml;
use Doctrine\ORM\ORMException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;

class ImportTransaction
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * ImportTransaction constructor.
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function importTransactionFromFile(UploadedFile $file, User $user, KernelInterface $kernel)
    {
        $transactionRepo = $this->doctrine->getRepository(Transaction::class);
        $manager = $this->doctrine->getManager();
        $filename = $this->saveFile($file);
        $extension = $file->getClientOriginalExtension();

        $command = 'php '.__DIR__.'/../../../bin/console transaction:import '.$filename.' '.$extension.' &';
        shell_exec($command);

        //$filename = "C:\\xampp\\htdocs\\kasa.radiotaxi919.pl\\src\\AppBundle\\Service//..//..//..//web//download//20181021212844.xml";
        /*
        $builder = new ProcessBuilder();
        switch ($extension){
            case 'xml':
                $this->readXmlFile($filename);
                break;
            case 'csv':
                $this->readCsvFile($filename);
                break;
            case 'xls':
                $this->readXlsFile($filename);
                break;
        }*/

    }

    protected function saveFile(UploadedFile $file)
    {
        $newFilename = (new \DateTime())->format('YmdHis') .
            "." . $file->getClientOriginalExtension();
        $result = $file->move($this->getUploadDir(), $newFilename);

        return $this->getUploadDir() . $newFilename;
    }

    protected function deleteFile($file)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($file);
    }

    protected function getDate($date)
    {
        $arrDate = explode(" ",$date);
        $dateParts = explode('.', $arrDate[0]);

        return new \DateTime(sprintf('%s-%s-%s %s',
            $dateParts[2], $dateParts[1], $dateParts[0], $arrDate[1]));
    }

    protected function getUploadDir()
    {
        return __DIR__ .'/../../../web/download/';
    }


    public function readXmlFile($filename)
    {
        //"C:\xampp\htdocs\kasa.radiotaxi919.pl\src\AppBundle\Service/../../../web/download/20181202220426.xml"
        $manager = $this->doctrine->getManager();
        $user = $this->doctrine->getRepository(User::class)
            ->findOneBy(['username' => 'system']);

        $driverRepo = $this->doctrine->getRepository(Driver::class);
        $transactionRepo = $this->doctrine->getRepository(Transaction::class);

        try{
            // Override PhpSpreadSheet Class XML
            $reader = new Xml();
            $spreadsheet = $reader->load($filename);
        }catch(Exception $e){

        }

        $sheet = $spreadsheet->getSheetByName('Transakcja');
        $rowIterator = $sheet->getRowIterator();
        foreach ($rowIterator as $row){
            if($row->getRowIndex() == 1){
                continue;
            }
            $transactionDate = new \DateTime(
                $sheet->getCell('F' . $row->getRowIndex())->getValue() . ' ' .
                $sheet->getCell('G'. $row->getRowIndex())->getValue()
            );

            $driverAmount = $sheet->getCell('N'. $row->getRowIndex())->getValue() +
                $sheet->getCell('S' . $row->getRowIndex())->getValue();

            $mid = $sheet->getCell('B'. $row->getRowIndex())->getValue();
            $driver = $driverRepo->findOneBy(['posNumberMid' => $mid]);

            $transactionID = $sheet->getCell('AB'.$row->getRowIndex())->getValue();
            $transaction = $transactionRepo->findOneBy(['taxiTransactionId' => $transactionID, 'transactionType' => Transaction::GOTOWKA]);

            if($transaction == null){
                $transaction = new Transaction();
                $settlement = new Settlement();
                $settlement
                    ->setTotalAmount($driverAmount)
                    ->setPercentage(0)
                    ->setSettledAmount(0)
                    ->setUser($user)
                ;
                $transaction->addSettlement($settlement);
            }

            $transaction
                ->setDriverId($driver)
                ->setClientId(null)
                ->setUser($user)
                ->setAuthCode($sheet->getCell('E'. $row->getRowIndex())->getValue())
                ->setAuthDate($transactionDate)
                ->setTaxiTransactionId($transactionID)
                ->setTransactionDate($transactionDate)
                ->setTransactionStatus('ACCEPTED')
                ->setTransactionStatusCode('000')
                ->setTotalAmount($sheet->getCell('N'. $row->getRowIndex())->getValue())
                ->setDriverAmount($driverAmount)
                ->setVat(0.08)
                ->setManual(1)

                ->setTaxiDriverId(null)
                ->setTaxiTerminalId(null)
                ->setTaxiCabId(null)
                ->setTaxiClientId(null)
                ->setTaxiPassengerId(null)
                ->setTaxiCardId(null)

                ->setCardAliasUsed('NO')
                ->setTaxiCardAliasId(0)
                ->setCorpoAliasUsed('NO')
                ->setTaxiCorpoAliasId(13)
                ->setPrice($sheet->getCell('N'. $row->getRowIndex())->getValue())
                ->setTip(0)
                ->setOriginalPan($sheet->getCell('H'. $row->getRowIndex())->getValue())
                ->setOriginalTid($sheet->getCell('D' . $row->getRowIndex())->getValue())
                ->setOriginalLicenseNumber(null)

                ->setCardType('PAYMENT_CARD')

                ->setSequenceId(0)
                ->setTransactionType(Transaction::GOTOWKA)
                ->setIsVoucher919(false)
                ->setVoucherNumber(null)
                ->setVoucherDescription(null)
                ->setIsSettled(false)
                ->setComment(null)
                ->setUpdateDate(new \DateTime())
                ;

            $manager->persist($transaction);
        }
        $manager->flush();
        $manager->clear();
    }

    public function readXlsFile($filename)
    {
    }

    public function readCsvFile($filename)
    {

        //        $reader = IOFactory::createReader(ucfirst($file->getClientOriginalExtension()));
        //        $reader->setReadDataOnly(true);
        //
        //        //$filename = $this->getUploadDir() . '20180308105751.xls';
        //        //$reader = IOFactory::createReader('Xls');
        //
        //        try{
        //            $spreadsheet = $reader->load($filename);
        //            $sheet = $spreadsheet->getSheet(0);
        //            $rows = $sheet->getRowIterator(2);
        //        }catch(Exception $e){
        //
        //        }
        //
        //        foreach ($rows as $row)
        //        {
        //            $i = $row->getRowIndex();
        //            $transactionId = $sheet->getCell('D'. $i)->getValue();
        //            $check = $transactionRepo->findOneBy([
        //                'taxiTransactionId' => $transactionId
        //            ]);
        //            if($check != null)
        //            {
        //                continue;
        //            }
        //
        //            $i = $row->getRowIndex();
        //            $authCode = $sheet->getCell('O'.$i)->getValue();
        //            $transactionDate = $this->getDate($sheet->getCell('B'.$i)->getValue());
        //
        //            $transactionStatus = HelperService::getTransactionStatusForImport($sheet->getCell('H'.$i)->getValue());
        //            $transactionStatusCode = HelperService::getTransactionStatusForImport($sheet->getCell('H'.$i)->getValue(), true);
        //            $totalAmount = $sheet->getCell('F'.$i)->getValue();
        //            $fee = $sheet->getCell('AM'.$i)->getValue();
        //            $originalPan = $sheet->getCell('N'.$i)->getValue();
        //            $cardType = $sheet->getCell('L'.$i)->getValue();
        //
        //            $transaction = new Transaction();
        //            $transaction
        //                ->setAuthCode($authCode)
        //                ->setAuthDate($transactionDate)
        //                ->setTaxiTransactionId($transactionId)
        //                ->setTransactionDate($transactionDate)
        //                ->setTransactionStatus($transactionStatus)
        //                ->setTransactionStatusCode($transactionStatusCode)
        //                ->setTotalAmount($totalAmount-$fee)
        //                ->setDriverAmount($transactionStatusCode=='000' ? ($totalAmount-$fee) : 0)
        //                ->setVat(0)
        //                ->setManual('TAK')
        ////                ->setTaxiDriverId(null)
        ////                ->setTaxiTerminalId(null)
        ////                ->setTaxiCabId(null)
        ////                ->setTaxiClientId(null)
        ////                ->setTaxiPassengerId(null)
        ////                ->setTaxiCardId(null)
        ////                ->setCardAliasUsed(null)
        ////                ->setTaxiCardAliasId(null)
        ////                ->setCorpoAliasUsed(null)
        ////                ->setTaxiCorpoAliasId(null)
        //                ->setPrice($totalAmount)
        //                ->setTip($fee)
        //                ->setOriginalPan($originalPan)
        //                //->setOriginalTid(null)
        //                //->setOriginalLicenseNumber(null)
        //                ->setCardType($cardType)
        //                //->setSequenceId(null)
        //                ->setTransactionType('import-file')
        //                //->setDriverId(null)
        //                //->setClientId(null)
        //                ->setIsVoucher919(false)
        //                ->setUser($user)
        //            ;
        //
        //            $percentage = 0;
        //            $settlement = new Settlement();
        //            $settlement->setTotalAmount($totalAmount-$fee)
        //                ->setPercentage($percentage)
        //                ->setSettledAmount(0)
        //                ->setUser($user)
        //            ;
        //            $transaction->addSettlement($settlement);
        //            $manager->persist($transaction);
        //        }
        //
        //        try{
        //            $manager->flush();
        //        }catch(\Exception $e){
        //
        //        }
    }
}
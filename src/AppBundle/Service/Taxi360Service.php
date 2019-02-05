<?php

namespace AppBundle\Service;

use AppBundle\Entity\ApiTaxi360\Card;
use AppBundle\Entity\ApiTaxi360\Client;
use AppBundle\Entity\ApiTaxi360\Driver;
use AppBundle\Entity\ApiTaxi360\Passenger;
use AppBundle\Entity\ApiTaxi360\Terminal;
use AppBundle\Entity\ApiTaxi360\Transaction;
use AppBundle\Entity\Params\TaxiSettings;
use AppBundle\Entity\Sequence;
use AppBundle\Entity\SequenceDB;
use AppBundle\Entity\Settlement;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use League\Csv\Writer;
use Symfony\Bridge\Doctrine\RegistryInterface;

class Taxi360Service
{
    /**
     * @var Registry $doctrine
     */
    private $doctrine;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var User
     */
    private $user;

    private $pdo;

    private $sequenceId;

    /**
     * @var TaxiSettings $appSettings
     */
    private $appSettings;

    private $sqlArray = [];

    private $moreDataAvailable = false;

    /**
     * Taxi360Service constructor.
     * @param Registry $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->pdo = $doctrine->getConnection();
        $this->manager = $doctrine->getManager();
        $this->appSettings = $this->doctrine->getRepository(TaxiSettings::class)->find(1);
    }
    
    public function importData($sequence, $new = true)
    {
        $this->user = $this->doctrine->getRepository(User::class)
            ->findOneBy(['username' => 'system']);

        /**
         * Rozdzielenie sekwencji na tablice
         * 0 - typ | 11 - podsumowanie | 1-10 pakiet danych
         */
        $sequenceArray = str_replace("\",", "\";",
            preg_split("/(DATA ;|FOOTER ;|HEADER ;)/", $sequence, null, PREG_SPLIT_NO_EMPTY)
        );
        $maxSequenceRow = count($sequenceArray)-1;

        /**
         * Ustawia SequenceID i status czy dostępne są jeszcze jakieś dane
         */
        $this->setSequenceIDAndDataStatus($sequenceArray, $maxSequenceRow);

        if($new == true){
            /**
             * Zapisanie oryginalnego pakietu do bazy danych
             */
            $this->saveSequenceDb($sequence);
        }

        $i = 1;
        while ($i < $maxSequenceRow)
        {
            $sequenceArray[$i] = str_replace('",','";',
                str_replace(' , ',';',
                    str_replace(';', ',',
                        str_replace('";"','""', $sequenceArray[$i])
                    )
                )
            );

            $seqDetail = array_map(function($item){
                // ObjectType=TransactionAccepted 0=1
                $kv = explode("=", trim($item));
                return array(
                    $kv[0] => preg_replace(
                        "/[^A-Za-z0-9ŚśĆćŹźŻżńąęŁłó@\/\s\-:._]/", " ", trim($kv[1],'"')
                    )
                );
            }, explode(";", $sequenceArray[$i]));
            // key-value z danymi
            $detail = array();
            foreach ($seqDetail as $items)
            {
                foreach ($items as $k=>$v) {
                    $detail[$k] = trim($v);
                }
            }

            switch ($detail['ObjectType']){
                case 'Card':
                    $this->cardType($detail);
                    break;
                case 'Client':
                    $this->clientType($detail);
                    break;
                case 'Driver':
                    $this->driverType($detail);
                    break;
                case 'Passenger':
                    $this->passengerType($detail);
                    break;
                case 'Terminal':
                    $this->terminalType($detail);
                    break;
                case 'TransactionAccepted':
                    $this->transactionAcceptedType($detail);
                    break;
            }
            $i++;
        }

        foreach ($this->sqlArray as $object) {
            $this->manager->persist($object);
        }
        $this->manager->flush();
        //$this->manager->clear();

        return array(
            'sequence' => $this->sequenceId,
            'moreDataAvailable' => $this->moreDataAvailable);
    }

    protected function cardType($object)
    {
        $card = $this->doctrine->getRepository(Card::class)
            ->findOneBy(array('taxiCardId' => $object['CardID']));
        if(NULL === $card){
            $card = new Card();
        }
        $client = $this->doctrine->getRepository(Client::class)
            ->findOneBy(array('taxiClientId' => $object['ClientID']));
        if($client != null){
            $this->manager->persist($client);
        }

        $passenger = $this->doctrine->getRepository(Passenger::class)
            ->findOneBy(array('taxiPassengerId' => $object['PassengerID']));
        if($passenger != null){
            $this->manager->persist($passenger);
        }

        // ACTIVE | BLOCKED | DEFINITION | NEW | RETIRED | SPENT
        $isActive = (!in_array($object['Status'], array('BLOCKED', 'RETIRED', 'SPENT'))) ? 1 : 0;

        $card
            ->setUser($this->user)
            ->setPan($object['PAN'])
            ->setTaxiCardId($object['CardID'])
            ->setTaxiClientId($object['ClientID'])
            ->setTaxiPassengerId($object['PassengerID'])
            ->setAction($object['Action'])
            ->setMovementDate(new \DateTime($object['MovementDate']))
            ->setCardType($object['CardType'])
            ->setStatus($object['Status'])
            ->setDailyLimit($object['DailyLimit'])
            ->setMonthlyLimit($object['MonthlyLimit'])
            ->setDailyUsage($object['DailyUsage'])
            ->setWorkingDays($object['WorkingDays'])
            ->setValidUntil(new \DateTime($object['ValidUntil']))
            ->setAvailableAmount($object['AvailableAmount'])
            ->setComment($object['Comment'])
            ->setDepartment(isset($object['Department']) ? $object['Department'] : NULL )
            ->setInfo1(isset($object['Info_1']) ? $object['Info_1'] : NULL)
            ->setInfo2(isset($object['Info_2']) ? $object['Info_2'] : NULL)
            ->setInfo3(isset($object['Info_3']) ? $object['Info_3'] : NULL)
            ->setMpk(isset($object['MPK']) ? $object['MPK'] : NULL)
            ->setSequenceId($this->sequenceId)
            ->setIsActive($isActive)
            ->setClient($client)
            ->setPassenger($passenger)
        ;

        $this->sqlArray['CARD'.$object['CardID']] = $card;
    }

    protected function clientType($object)
    {
        $client = $this->doctrine->getRepository(Client::class)
            ->findOneBy(array('taxiClientId' => (int)$object['ClientID']));
        if(NULL === $client)
        {
            $client = new Client();
        }
        $client
            ->setUser($this->user)
            ->setTaxiClientId($object['ClientID'])
            ->setAction($object['Action'])
            ->setMovementDate(new \DateTime($object['MovementDate']))
            ->setName($object['Name'])
            ->setNip($object['NIP'])
            ->setRegon($object['Regon'])
            ->setKrs($object['KRS'])
            //->setEmail($object['Email'])
            ->setMonthlyLimit($object['MonthlyLimit'])
            ->setAccountingMode($object['AccountingMode'])
            ->setAllowExternalInvoice($object['AllowExternalInvoice'])
            ->setAllowVoucher($object['AllowVoucher'])
            ->setStatus($object['Status'])
            ->setCardMonthlyLimit($object['CardMonthlyLimit'])
            ->setCardDailyLimit($object['CardDailyLimit'])
            ->setCardDailyUsage($object['CardDailyUsage'])
            ->setCardWorkingDays($object['CardWorkingDays'])
            ->setVoucherMonthlyLimit($object['VoucherMonthlyLimit'])
            ->setVoucherLimit($object['VoucherLimit'])
            ->setVoucherMaxCount($object['VoucherMaxCount'])

            ->setAddressStreet($object['Address.Street'])
            ->setAddressPostalCode($object['Address.PostalCode'])
            ->setAddressTown($object['Address.Town'])
            ->setAddressCountry($object['Address.Country'])

            ->setMailingAddressStreet($object['MailingAddress.Street'])
            ->setMailingAddressPostalCode($object['MailingAddress.PostalCode'])
            ->setMailingAddressTown($object['MailingAddress.Town'])
            ->setMailingAddressCountry($object['MailingAddress.Country'])
            //->setPhoneNumber($object['Phone.Number'])
            //->setAltPhoneNumber($object['AltPhone.Number'])
            ->setSequenceId($this->sequenceId)
            ->setAgreementNumber($object['AgreementNumber'])
            ->setAgreementUntil(new \DateTime($object['AgreementUntil']))
        ;

        $this->sqlArray['CLIENT'.$object['ClientID']] = $client;
    }

    protected function driverType($object)
    {
        $driver = $this->doctrine->getRepository(Driver::class)
            ->findOneBy(array('taxiDriverId' => $object['DriverID']));
        if($driver === NULL)
        {
            $driver = new Driver();
        }
        $driver
            ->setUser($this->user)
            ->setTaxiDriverId($object['DriverID'])
            ->setAction($object['Action'])
            ->setMovementDate(new \DateTime($object['MovementDate']))
            ->setLicenseNumber($object['LicenseNumber'])
            ->setFirstName($object['FirstName'])
            ->setSurname($object['Surname'])
            ->setAddressStreet($object['Address.Street'])
            ->setAddressPostalCode($object['Address.PostalCode'])
            ->setAddressTown($object['Address.Town'])
            ->setAddressCountry($object['Address.Country'])
            ->setMailingAddressStreet($object['MailingAddress.Street'])
            ->setMailingAddressPostalCode($object['MailingAddress.PostalCode'])
            ->setMailingAddressTown($object['MailingAddress.Town'])
            ->setMailingAddressCountry($object['MailingAddress.Country'])
            ->setPrepaid($object['Prepaid'])
            ->setCabSideNumber($object['Cab.SideNumber'])
            ->setCabRegistrationNumber($object['Cab.RegistrationNumber'])
            ->setTaxiterminalId($object['TerminalID'])
            ->setTerminalTid($object['Terminal.TID'])
            ->setTerminalModel($object['Terminal.Model'])
            ->setStatus($object['Status'])
            ->setIsEmployee($object['IsEmployee'])
            ->setInvoiceType($object['InvoiceType'])
            ->setFirmName($object['Name'])
            ->setNip($object['NIP'])
            ->setAllowExternalInvoice($object['AllowExternalInvoice'])
            ->setInvoiceGoodsReferenceVat($object['InvoiceGoodsReference.VAT'])
            ->setInvoiceGoodsReferenceDescription($object['InvoiceGoodsReference.Description'])
            ->setSequenceId($this->sequenceId)
            ;

        $this->sqlArray['DRIVER'.$object['DriverID']] = $driver;
    }

    protected function passengerType($object)
    {
        $passenger = $this->doctrine->getRepository(Passenger::class)
            ->findOneBy(array('taxiPassengerId' => $object['PassengerID']));
        if(NULL === $passenger)
        {
            $passenger = new Passenger();
        }

        $passenger
            ->setUser($this->user)
            ->setTaxiPassengerId($object['PassengerID'])
            ->setAction($object['Action'])
            ->setMovementDate(new \DateTime($object['MovementDate']))
            ->setTaxiClientId($object['ClientID'])
            ->setFirstName($object['FirstName'])
            ->setSurname($object['Surname'])
            ->setDepartment($object['Department'])
            ->setPosition($object['Position'])
            ->setNumber($object['Number'])
            ->setStatus($object['Status'])
            ->setMobileNumber($object['Mobile.Number'])
            ->setOperatorLoginName($object['Operator.LoginName'])
            ->setOperatorStatus($object['Operator.Status'])
            ->setSequenceId($this->sequenceId)
            ;

        $this->sqlArray['PASSENGER'.$object['PassengerID']] = $passenger;
    }

    protected function terminalType($object)
    {
        $terminal = $this->doctrine->getRepository(Terminal::class)
            ->findOneBy(array('taxiTerminalId' => $object['TerminalID'] ));

        if($terminal == NULL)
        {
            $terminal = new Terminal();
        }
        $terminal
            ->setUser($this->user)
            ->setTid($object['TID'])
            ->setTaxiTerminalId($object['TerminalID'])
            ->setAction($object['Action'])
            ->setMovementDate(new \DateTime($object['MovementDate']))
            ->setModel($object['Model'])
            ->setStatus($object['Status'])
            ->setTaxiDriverId($object['DriverID'])
            ->setInterfaceId($object['InterfaceID'])
            ->setInterfaceModel($object['Interface.Model'])
            ->setInterfaceSerialNumber($object['Interface.SerialNumber'])
            ->setSimCardId($object['SimCardID'])
            ->setSimCardServiceProvider($object['SimCard.ServiceProvider'])
            ->setSimCardSerialNumber($object['SimCard.SerialNumber'])
            ->setSimCardPhoneNumber($object['SimCard.PhoneNumber'])
            ->setSimCardIPAddress($object['SimCard.IPAddress'])
            ->setSequenceId($this->sequenceId)
        ;
        $this->sqlArray['TERMINAL'.$object['TerminalID']] = $terminal;
    }

    /**
     * Zapisanie do bazy pobranych transakcji
     * @param $object
     */
    protected function transactionAcceptedType($object)
    {
        $newTransaction = false;
        $transaction = $this->doctrine->getRepository(Transaction::class)
            ->findOneBy(array('taxiTransactionId' => (int)$object['TransactionID']));
        if(NULL === $transaction)
        {
            $transaction = new Transaction();
            $newTransaction = true;
        }
        $driver = $this->doctrine->getRepository(Driver::class)
            ->findOneBy(array('taxiDriverId' => (int)$object['DriverID']));
        if($driver != null){
            $this->manager->persist($driver);
        }
        $client = $this->doctrine->getRepository(Client::class)
            ->findOneBy(array('taxiClientId' => (int)$object['ClientID']));
        if($client != null){
            $this->manager->persist($client);
        }

        $transaction
            ->setAuthCode($object['AuthCode'])
            ->setAuthDate(new \DateTime($object['AuthDate']))
            ->setTaxiTransactionId($object['TransactionID'])
            ->setTransactionDate(new \DateTime($object['TransactionDate']))
            ->setTransactionStatus($object['TransactionStatus'])
            ->setTransactionStatusCode($object['TransactionStatusCode'])
            ->setTotalAmount($object['TotalAmount'])
            ->setDriverAmount($object['DriverAmount'])
            ->setVat($object['VAT'])
            ->setManual($object['Manual'])
            ->setTaxiDriverId($object['DriverID'])
            ->setTaxiTerminalId($object['TerminalID'])
            ->setTaxiCabId($object['CabID'])
            ->setTaxiClientId($object['ClientID'])
            ->setTaxiPassengerId($object['PassengerID'])
            ->setTaxiCardId($object['CardID'])
            ->setCardAliasUsed($object['CardAliasUsed'])
            ->setTaxiCardAliasId($object['CardAliasID'])
            ->setCorpoAliasUsed($object['CorpoAliasUsed'])
            ->setTaxiCorpoAliasId($object['CorpoAliasID'])
            ->setPrice($object['Price'])
            ->setTip($object['Tip'])
            ->setOriginalPan($object['OriginalPAN'])
            ->setOriginalTid($object['OriginalTID'])
            ->setOriginalLicenseNumber($object['OriginalLicenseNumber'])
            ->setCardType($object['CardType'])
            ->setSequenceId($this->sequenceId)
            ->setTransactionType(Transaction::BEZGOTOWKA)

            ->setDriverId($driver)
            ->setClientId($client)
            ->setIsVoucher919(false)
            ->setUser($this->user)
            ;

        /**
         * Jeżeli transakcja jest nowa i nie ma status odrzuconej to
         * utwórz dla niej linię rozliczenia
         */
        if($newTransaction == true && $object['TransactionStatus'] != 'REJECTED')
        {
            $percentage = 0;
            if($client != NULL && $client->getOwnProvision() == true)
            {
                if($object['CardType'] == Card::TYPE_STANDARD)
                {
                    $percentage = $client->getCardProvision();
                }
                elseif($object['CardType'] == Card::TYPE_VOUCHER){
                    $percentage = $client->getVoucherProvision();
                }
                elseif ($object['CardType'] == Card::TYPE_eVOUCHER){
                    $percentage = $client->getEVoucherProvision();
                };
            }else{
                if($object['CardType'] == Card::TYPE_STANDARD)
                {
                    $percentage = $this->appSettings->getCard();
                }
                elseif($object['CardType'] == Card::TYPE_VOUCHER){
                    $percentage = $this->appSettings->getVoucher();
                }
                elseif ($object['CardType'] == Card::TYPE_eVOUCHER) {
                    $percentage = $this->appSettings->getEVoucher();
                };
            }

            $settlement = new Settlement();
            $settlement->setTotalAmount($object['TotalAmount'])
                ->setPercentage($percentage)
                ->setSettledAmount(0)
                ->setUser($this->user)
            ;
            $transaction->addSettlement($settlement);
        }

        $this->sqlArray['TRANSACTION'.$object['TransactionID']] = $transaction;
    }

    /**
     * Zapisanie oryginalnej sekwencji do bazy danych
     * @param $sequence
     */
    protected function saveSequenceDb($sequence)
    {
        $sql = "REPLACE INTO sequence_db(sequence_id, content, create_date) VALUES (:id, :content, :createDate)";

        /** @var \PDOStatement $stmt */
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(
            ':id' => $this->sequenceId,
            ':content' => $sequence,
            ':createDate' => (new \DateTime())->format('Y-m-d H:i:s')
        ));
    }

    /**
     * Generuje HEADER and FOOTER - stary pomysł
     * @param $sequenceArray
     * @param $maxSequenceRow
     */
    protected function setSequenceIDAndDataStatus($sequenceArray, $maxSequenceRow){
        /** Połącz tablice HEADER i FOOTER  */
        $headerArray = array_merge(
            array_map('trim', explode(";", $sequenceArray[0])),
            array_map('trim', explode(";", $sequenceArray[$maxSequenceRow]))
        );

        foreach ($headerArray as $key => $value)
        {
            $keyValue = explode("=", $value);
            if($keyValue[0] == 'SequenceID'){
                $this->sequenceId = $keyValue[1];
            }elseif ($keyValue[0] == 'MoreDataAvailable')
            {
                $this->moreDataAvailable = trim($keyValue[1]) == 'YES' ? true : false;
            }
        }
    }

}
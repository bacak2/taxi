<?php

namespace AppBundle\Entity\ApiTaxi360;

use AppBundle\Service\HelperService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 * @ORM\Table(name="transaction")
 * @ORM\HasLifecycleCallbacks()
 */
class Transaction
{
    const BEZGOTOWKA = 'bezgotowka';
    const GOTOWKA = 'importpko';
    const WSZYSTKIE = 'wszystkie';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /* TAXI360 */
    /**
     * @ORM\Column(type="string", nullable=true, name="auth_code")
     */
    private $authCode;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="auth_date")
     */
    private $authDate;

    /**
     * @ORM\Column(type="text", length=30, name="taxi_transaction_id", nullable=true)
     */
    private $taxiTransactionId;

    /**
     * @ORM\Column(type="datetime", name="transaction_date", nullable=true)
     */
    private $transactionDate;

    /**
     * @ORM\Column(type="string", name="transaction_status", nullable=true)
     */
    private $transactionStatus;

    /**
     * @ORM\Column(type="string", name="transaction_status_code", nullable=true)
     */
    private $transactionStatusCode;

    /**
     * @ORM\Column(type="decimal", scale=2, name="total_amount", nullable=true)
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="decimal", scale=2, name="driver_amount", nullable=true)
     */
    private $driverAmount;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $manual;

    /**
     * @ORM\Column(type="integer", name="taxi_driver_id", nullable=true)
     */
    private $taxiDriverId;

    /**
     * @ORM\Column(type="integer", name="taxi_terminal_id", nullable=true)
     */
    private $taxiTerminalId;

    /**
     * @ORM\Column(type="integer", name="taxi_cab_id", nullable=true)
     */
    private $taxiCabId;

    /**
     * @ORM\Column(type="integer", name="taxi_client_id", nullable=true)
     */
    private $taxiClientId;

    /**
     * @ORM\Column(type="integer", name="taxi_passenger_id", nullable=true)
     */
    private $taxiPassengerId;

    /**
     * @ORM\Column(type="integer", name="taxi_card_id", nullable=true)
     */
    private $taxiCardId;

    /**
     * @ORM\Column(type="string", name="card_alias_used", nullable=true)
     */
    private $cardAliasUsed;

    /**
     * @ORM\Column(type="integer", name="taxi_card_alias_id", nullable=true)
     */
    private $taxiCardAliasId;

    /**
     * @ORM\Column(type="string", name="corpo_alias_used", nullable=true)
     */
    private $corpoAliasUsed;

    /**
     * @ORM\Column(type="integer", name="taxi_corpo_alias_id", nullable=true)
     */
    private $taxiCorpoAliasId;

    /**
     * @ORM\Column(type="decimal", scale=2 ,nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $tip;

    /**
     * @ORM\Column(type="string", name="original_pan", nullable=true)
     */
    private $originalPan;

    /**
     * @ORM\Column(type="string", name="original_tid", nullable=true)
     */
    private $originalTid;

    /**
     * @ORM\Column(type="string", name="original_license_number", nullable=true)
     */
    private $originalLicenseNumber;

    /**
     * @ORM\Column(type="string", name="card_type", nullable=true)
     */
    private $cardType;

    /**
     * @ORM\Column(type="integer", name="sequence_id", nullable=true)
     */
    private $sequenceId;

    /****************************** KONIEC API *****************************************/

    /**
     *
     * @ORM\Column(type="string", name="transaction_type", nullable=true)
     */
    private $transactionType;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\ApiTaxi360\Driver"
     * )
     * @ORM\JoinColumn(
     *     name="driver_id",
     *     referencedColumnName="id",
     *     nullable=true
     * )
     */
    private $driverId;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\ApiTaxi360\Client"
     * )
     * @ORM\JoinColumn(
     *     name="client_id",
     *     referencedColumnName="id",
     *     nullable=true
     * )
     */
    private $clientId;

    /**
     * @ORM\Column(type="boolean", name="is_voucher919")
     */
    private $isVoucher919 = false;

    /**
     * @ORM\Column(type="string", name="voucher_number", nullable=true)
     */
    private $voucherNumber;

    /**
     * @ORM\Column(type="string", name="voucher_description", nullable=true)
     */
    private $voucherDescription;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Settlement",
     *     mappedBy="transaction",
     *     cascade={"persist"}
     * )
     */
    private $settlements;

    /**
     * @ORM\Column(type="boolean", name="is_settled")
     */
    private $isSettled = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $user;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Invoice\Invoice"
     * )
     * @ORM\JoinColumn(
     *     name="client_invoice_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    private $clientInvoice;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Invoice\Invoice"
     * )
     * @ORM\JoinColumn(
     *     name="driver_invoice_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    private $driverInvoice;

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        $this->settlements = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->updateDate = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set authCode.
     *
     * @param string|null $authCode
     *
     * @return Transaction
     */
    public function setAuthCode($authCode = null)
    {
        $this->authCode = $authCode;

        return $this;
    }

    /**
     * Get authCode.
     *
     * @return string|null
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Set authDate.
     *
     * @param \DateTime|null $authDate
     *
     * @return Transaction
     */
    public function setAuthDate($authDate = null)
    {
        $this->authDate = $authDate;

        return $this;
    }

    /**
     * Get authDate.
     *
     * @return \DateTime|null
     */
    public function getAuthDate()
    {
        return $this->authDate;
    }

    /**
     * Set taxiTransactionId.
     *
     * @param int|null $taxiTransactionId
     *
     * @return Transaction
     */
    public function setTaxiTransactionId($taxiTransactionId = null)
    {
        $this->taxiTransactionId = $taxiTransactionId;

        return $this;
    }

    /**
     * Get taxiTransactionId.
     *
     * @return int|null
     */
    public function getTaxiTransactionId()
    {
        return $this->taxiTransactionId;
    }

    /**
     * Set transactionDate.
     *
     * @param \DateTime|null $transactionDate
     *
     * @return Transaction
     */
    public function setTransactionDate($transactionDate = null)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate.
     *
     * @return \DateTime|null
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set transactionStatus.
     *
     * @param string|null $transactionStatus
     *
     * @return Transaction
     */
    public function setTransactionStatus($transactionStatus = null)
    {
        $this->transactionStatus = $transactionStatus;

        return $this;
    }

    /**
     * Get transactionStatus.
     *
     * @return string|null
     */
    public function getTransactionStatus()
    {
        return $this->transactionStatus;
    }

    /**
     * Set transactionStatusCode.
     *
     * @param string|null $transactionStatusCode
     *
     * @return Transaction
     */
    public function setTransactionStatusCode($transactionStatusCode = null)
    {
        $this->transactionStatusCode = $transactionStatusCode;

        return $this;
    }

    /**
     * Get transactionStatusCode.
     *
     * @return string|null
     */
    public function getTransactionStatusCode()
    {
        return $this->transactionStatusCode;
    }

    /**
     * Set totalAmount.
     *
     * @param string|null $totalAmount
     *
     * @return Transaction
     */
    public function setTotalAmount($totalAmount = null)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount.
     *
     * @return string|null
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set driverAmount.
     *
     * @param string|null $driverAmount
     *
     * @return Transaction
     */
    public function setDriverAmount($driverAmount = null)
    {
        $this->driverAmount = $driverAmount;

        return $this;
    }

    /**
     * Get driverAmount.
     *
     * @return string|null
     */
    public function getDriverAmount()
    {
        return $this->driverAmount;
    }

    /**
     * Set vat.
     *
     * @param string|null $vat
     *
     * @return Transaction
     */
    public function setVat($vat = null)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat.
     *
     * @return string|null
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set manual.
     *
     * @param bool|null $manual
     *
     * @return Transaction
     */
    public function setManual($manual = null)
    {
        $this->manual = $manual;

        return $this;
    }

    /**
     * Get manual.
     *
     * @return bool|null
     */
    public function getManual()
    {
        return $this->manual;
    }

    /**
     * Set taxiDriverId.
     *
     * @param int|null $taxiDriverId
     *
     * @return Transaction
     */
    public function setTaxiDriverId($taxiDriverId = null)
    {
        $this->taxiDriverId = $taxiDriverId;

        return $this;
    }

    /**
     * Get taxiDriverId.
     *
     * @return int|null
     */
    public function getTaxiDriverId()
    {
        return $this->taxiDriverId;
    }

    /**
     * Set taxiTerminalId.
     *
     * @param int|null $taxiTerminalId
     *
     * @return Transaction
     */
    public function setTaxiTerminalId($taxiTerminalId = null)
    {
        $this->taxiTerminalId = $taxiTerminalId;

        return $this;
    }

    /**
     * Get taxiTerminalId.
     *
     * @return int|null
     */
    public function getTaxiTerminalId()
    {
        return $this->taxiTerminalId;
    }

    /**
     * Set taxiCabId.
     *
     * @param int|null $taxiCabId
     *
     * @return Transaction
     */
    public function setTaxiCabId($taxiCabId = null)
    {
        $this->taxiCabId = $taxiCabId;

        return $this;
    }

    /**
     * Get taxiCabId.
     *
     * @return int|null
     */
    public function getTaxiCabId()
    {
        return $this->taxiCabId;
    }

    /**
     * Set taxiClientId.
     *
     * @param int|null $taxiClientId
     *
     * @return Transaction
     */
    public function setTaxiClientId($taxiClientId = null)
    {
        $this->taxiClientId = $taxiClientId;

        return $this;
    }

    /**
     * Get taxiClientId.
     *
     * @return int|null
     */
    public function getTaxiClientId()
    {
        return $this->taxiClientId;
    }

    /**
     * Set taxiPassengerId.
     *
     * @param int|null $taxiPassengerId
     *
     * @return Transaction
     */
    public function setTaxiPassengerId($taxiPassengerId = null)
    {
        $this->taxiPassengerId = $taxiPassengerId;

        return $this;
    }

    /**
     * Get taxiPassengerId.
     *
     * @return int|null
     */
    public function getTaxiPassengerId()
    {
        return $this->taxiPassengerId;
    }

    /**
     * Set taxiCardId.
     *
     * @param int|null $taxiCardId
     *
     * @return Transaction
     */
    public function setTaxiCardId($taxiCardId = null)
    {
        $this->taxiCardId = $taxiCardId;

        return $this;
    }

    /**
     * Get taxiCardId.
     *
     * @return int|null
     */
    public function getTaxiCardId()
    {
        return $this->taxiCardId;
    }

    /**
     * Set cardAliasUsed.
     *
     * @param string|null $cardAliasUsed
     *
     * @return Transaction
     */
    public function setCardAliasUsed($cardAliasUsed = null)
    {
        $this->cardAliasUsed = $cardAliasUsed;

        return $this;
    }

    /**
     * Get cardAliasUsed.
     *
     * @return string|null
     */
    public function getCardAliasUsed()
    {
        return $this->cardAliasUsed;
    }

    /**
     * Set taxiCardAliasId.
     *
     * @param int|null $taxiCardAliasId
     *
     * @return Transaction
     */
    public function setTaxiCardAliasId($taxiCardAliasId = null)
    {
        $this->taxiCardAliasId = $taxiCardAliasId;

        return $this;
    }

    /**
     * Get taxiCardAliasId.
     *
     * @return int|null
     */
    public function getTaxiCardAliasId()
    {
        return $this->taxiCardAliasId;
    }

    /**
     * Set corpoAliasUsed.
     *
     * @param string|null $corpoAliasUsed
     *
     * @return Transaction
     */
    public function setCorpoAliasUsed($corpoAliasUsed = null)
    {
        $this->corpoAliasUsed = $corpoAliasUsed;

        return $this;
    }

    /**
     * Get corpoAliasUsed.
     *
     * @return string|null
     */
    public function getCorpoAliasUsed()
    {
        return $this->corpoAliasUsed;
    }

    /**
     * Set taxiCorpoAliasId.
     *
     * @param int|null $taxiCorpoAliasId
     *
     * @return Transaction
     */
    public function setTaxiCorpoAliasId($taxiCorpoAliasId = null)
    {
        $this->taxiCorpoAliasId = $taxiCorpoAliasId;

        return $this;
    }

    /**
     * Get taxiCorpoAliasId.
     *
     * @return int|null
     */
    public function getTaxiCorpoAliasId()
    {
        return $this->taxiCorpoAliasId;
    }

    /**
     * Set price.
     *
     * @param string|null $price
     *
     * @return Transaction
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set tip.
     *
     * @param string|null $tip
     *
     * @return Transaction
     */
    public function setTip($tip = null)
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * Get tip.
     *
     * @return string|null
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * Set originalPan.
     *
     * @param string|null $originalPan
     *
     * @return Transaction
     */
    public function setOriginalPan($originalPan = null)
    {
        $this->originalPan = $originalPan;

        return $this;
    }

    /**
     * Get originalPan.
     *
     * @return string|null
     */
    public function getOriginalPan()
    {
        return $this->originalPan;
    }

    /**
     * Set originalTid.
     *
     * @param string|null $originalTid
     *
     * @return Transaction
     */
    public function setOriginalTid($originalTid = null)
    {
        $this->originalTid = $originalTid;

        return $this;
    }

    /**
     * Get originalTid.
     *
     * @return string|null
     */
    public function getOriginalTid()
    {
        return $this->originalTid;
    }

    /**
     * Set originalLicenseNumber.
     *
     * @param string|null $originalLicenseNumber
     *
     * @return Transaction
     */
    public function setOriginalLicenseNumber($originalLicenseNumber = null)
    {
        $this->originalLicenseNumber = $originalLicenseNumber;

        return $this;
    }

    /**
     * Get originalLicenseNumber.
     *
     * @return string|null
     */
    public function getOriginalLicenseNumber()
    {
        return $this->originalLicenseNumber;
    }

    /**
     * Set cardType.
     *
     * @param string|null $cardType
     *
     * @return Transaction
     */
    public function setCardType($cardType = null)
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * Get cardType.
     *
     * @return string|null
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Set sequenceId.
     *
     * @param int|null $sequenceId
     *
     * @return Transaction
     */
    public function setSequenceId($sequenceId = null)
    {
        $this->sequenceId = $sequenceId;

        return $this;
    }

    /**
     * Get sequenceId.
     *
     * @return int|null
     */
    public function getSequenceId()
    {
        return $this->sequenceId;
    }

    /**
     * Set hash.
     *
     * @param string $hash
     *
     * @return Transaction
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Transaction
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate.
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set driverId.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Driver|null $driverId
     *
     * @return Transaction
     */
    public function setDriverId(\AppBundle\Entity\ApiTaxi360\Driver $driverId = null)
    {
        $this->driverId = $driverId;

        return $this;
    }

    /**
     * Get driverId.
     *
     * @return \AppBundle\Entity\ApiTaxi360\Driver|null
     */
    public function getDriverId()
    {
        return $this->driverId;
    }

    /**
     * Set clientId.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Client|null $clientId
     *
     * @return Transaction
     */
    public function setClientId(\AppBundle\Entity\ApiTaxi360\Client $clientId = null)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId.
     *
     * @return \AppBundle\Entity\ApiTaxi360\Client|null
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Transaction
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     * @return Transaction
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsVoucher919()
    {
        return $this->isVoucher919;
    }

    /**
     * @param mixed $isVoucher919
     * @return Transaction
     */
    public function setIsVoucher919($isVoucher919)
    {
        $this->isVoucher919 = $isVoucher919;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoucherNumber()
    {
        return $this->voucherNumber;
    }

    /**
     * @param mixed $voucherNumber
     * @return Transaction
     */
    public function setVoucherNumber($voucherNumber)
    {
        $this->voucherNumber = $voucherNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoucherDescription()
    {
        return $this->voucherDescription;
    }

    /**
     * @param mixed $voucherDescription
     * @return Transaction
     */
    public function setVoucherDescription($voucherDescription)
    {
        $this->voucherDescription = $voucherDescription;
        return $this;
    }

    /**
     * Set transactionType.
     *
     * @param string|null $transactionType
     *
     * @return Transaction
     */
    public function setTransactionType($transactionType = null)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType.
     *
     * @return string|null
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Add settlement.
     *
     * @param \AppBundle\Entity\Settlement $settlement
     *
     * @return Transaction
     */
    public function addSettlement(\AppBundle\Entity\Settlement $settlement)
    {
        $settlement->setTransaction($this);
        $this->settlements[] = $settlement;

        return $this;
    }

    /**
     * Remove settlement.
     *
     * @param \AppBundle\Entity\Settlement $settlement
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSettlement(\AppBundle\Entity\Settlement $settlement)
    {
        $settlement->setTransaction(null);
        return $this->settlements->removeElement($settlement);
    }

    /**
     * Get settlements.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSettlements()
    {
        return $this->settlements;
    }

    /**
     * @return mixed
     */
    public function getisSettled()
    {
        return $this->isSettled;
    }

    /**
     * @param mixed $isSettled
     * @return Transaction
     */
    public function setIsSettled($isSettled)
    {
        $this->isSettled = $isSettled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientInvoice()
    {
        return $this->clientInvoice;
    }

    /**
     * @param mixed $clientInvoice
     * @return Transaction
     */
    public function setClientInvoice($clientInvoice)
    {
        $this->clientInvoice = $clientInvoice;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDriverInvoice()
    {
        return $this->driverInvoice;
    }

    /**
     * @param mixed $driverInvoice
     * @return Transaction
     */
    public function setDriverInvoice($driverInvoice)
    {
        $this->driverInvoice = $driverInvoice;
        return $this;
    }

}

<?php

namespace AppBundle\Entity\ApiTaxi360;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 * @ORM\Table(name="client")
 * @ORM\HasLifecycleCallbacks()
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    private $user;

    //TAXI 360

    /**
     * @ORM\Column(type="integer", name="taxi_client_id", nullable=true)
     */
    private $taxiClientId;

    /**
     * @ORM\Column(type="string", nullable=true)
     * Insert | Update
     */
    private $action;

    /**
     * @ORM\Column(type="datetime", name="movement_date")
     */
    private $movementDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $nip;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $regon;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $krs;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="decimal", scale=2, name="monthly_limit", nullable=true)
     */
    private $monthlyLimit;

    /**
     * @ORM\Column(type="string", name="accounting_mode", nullable=true)
     */
    private $accountingMode;

    /**
     * @ORM\Column(type="string", name="allow_external_invoice", nullable=true)
     */
    private $allowExternalInvoice;

    /**
     * @ORM\Column(type="string", name="allow_voucher", nullable=true)
     */
    private $allowVoucher;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", scale=2, name="card_monthly_limit", nullable=true)
     */
    private $cardMonthlyLimit;

    /**
     * @ORM\Column(type="decimal", scale=2, name="card_daily_limit", nullable=true)
     */
    private $cardDailyLimit;

    /**
     * @ORM\Column(type="integer", name="card_daily_usage", nullable=true)
     */
    private $cardDailyUsage;

    /**
     * @ORM\Column(type="string", name="card_working_days", nullable=true)
     */
    private $cardWorkingDays;

    /**
     * @ORM\Column(type="decimal", scale=2, name="voucher_monthly_limit", nullable=true)
     */
    private $voucherMonthlyLimit;

    /**
     * @ORM\Column(type="decimal", scale=2, name="voucher_limit", nullable=true)
     */
    private $voucherLimit;

    /**
     * @ORM\Column(type="integer", name="voucher_max_count", nullable=true)
     */
    private $voucherMaxCount;

    /**
     * @ORM\Column(type="string", name="address_street", nullable=true)
     */
    private $addressStreet;

    /**
     * @ORM\Column(type="string", name="address_postal_code", nullable=true)
     */
    private $addressPostalCode;

    /**
     * @ORM\Column(type="string", name="address_town", nullable=true)
     */
    private $addressTown;

    /**
     * @ORM\Column(type="string", name="address_country", nullable=true)
     */
    private $addressCountry;

    /**
     * @ORM\Column(type="string", name="mailing_address_street", nullable=true)
     */
    private $mailingAddressStreet;

    /**
     * @ORM\Column(type="string", name="mailing_address_postal_code", nullable=true)
     */
    private $mailingAddressPostalCode;

    /**
     * @ORM\Column(type="string", name="mailing_address_town", nullable=true)
     */
    private $mailingAddressTown;

    /**
     * @ORM\Column(type="string", name="mailing_address_country", nullable=true)
     */
    private $mailingAddressCountry;

    /**
     * @ORM\Column(type="string", name="phone_number", nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", name="alt_phone_number", nullable=true)
     */
    private $altPhoneNumber;

    /**
     * @ORM\Column(type="integer", name="sequence_id")
     */
    private $sequenceId;

    /************************* KONIEC API ********************************************/

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\ApiTaxi360\Card",
     *     mappedBy="client",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    private $cards;


    /************************* UMOWA ********************************************/
    /**
     * @ORM\Column(type="string", name="agreement_number", nullable=true)
     */
    private $agreementNumber;

    /**
     * @ORM\Column(type="date", name="agreement_until", nullable=true)
     */
    private $agreementUntil;

    /**
     * @ORM\Column(type="integer", nullable=true, name="payment_days")
     */
    private $paymentDays = 14;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $discount = 0;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $cardProvision;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $eVoucherProvision;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $voucherProvision;

    /**
     * @ORM\Column(type="boolean", name="own_provision")
     */
    private $ownProvision = false;
    /************************* KONIEC UMOWY ********************************************/

    /**
     * @ORM\Column(type="boolean", name="skip_invoice", nullable=true)
     */
    private $skipInvoice;

    /**
     * @ORM\Column(type="string", nullable=true, name="invoice_name")
     */
    private $invoiceName;

    /**
     * @ORM\Column(type="string", nullable=true, name="invoice_sign")
     */
    private $invoiceSign;

    /**
     * @ORM\Column(type="string", nullable=true, name="invoice_email")
     */
    private $invoiceEmail;

    /**
     * @ORM\Column(type="string", nullable=true, name="envelope_sign")
     */
    private $envelopeSign;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

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
     * Set taxiClientId.
     *
     * @param int|null $taxiClientId
     *
     * @return Client
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
     * Set action.
     *
     * @param string|null $action
     *
     * @return Client
     */
    public function setAction($action = null)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action.
     *
     * @return string|null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set movementDate.
     *
     * @param \DateTime $movementDate
     *
     * @return Client
     */
    public function setMovementDate($movementDate)
    {
        $this->movementDate = $movementDate;

        return $this;
    }

    /**
     * Get movementDate.
     *
     * @return \DateTime
     */
    public function getMovementDate()
    {
        return $this->movementDate;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Client
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nip.
     *
     * @param string|null $nip
     *
     * @return Client
     */
    public function setNip($nip = null)
    {
        $this->nip = $nip;

        return $this;
    }

    /**
     * Get nip.
     *
     * @return string|null
     */
    public function getNip()
    {
        return $this->nip;
    }

    /**
     * Set regon.
     *
     * @param string|null $regon
     *
     * @return Client
     */
    public function setRegon($regon = null)
    {
        $this->regon = $regon;

        return $this;
    }

    /**
     * Get regon.
     *
     * @return string|null
     */
    public function getRegon()
    {
        return $this->regon;
    }

    /**
     * Set krs.
     *
     * @param string|null $krs
     *
     * @return Client
     */
    public function setKrs($krs = null)
    {
        $this->krs = $krs;

        return $this;
    }

    /**
     * Get krs.
     *
     * @return string|null
     */
    public function getKrs()
    {
        return $this->krs;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Client
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set monthlyLimit.
     *
     * @param string|null $monthlyLimit
     *
     * @return Client
     */
    public function setMonthlyLimit($monthlyLimit = null)
    {
        $this->monthlyLimit = $monthlyLimit;

        return $this;
    }

    /**
     * Get monthlyLimit.
     *
     * @return string|null
     */
    public function getMonthlyLimit()
    {
        return $this->monthlyLimit;
    }

    /**
     * Set accountingMode.
     *
     * @param string|null $accountingMode
     *
     * @return Client
     */
    public function setAccountingMode($accountingMode = null)
    {
        $this->accountingMode = $accountingMode;

        return $this;
    }

    /**
     * Get accountingMode.
     *
     * @return string|null
     */
    public function getAccountingMode()
    {
        return $this->accountingMode;
    }

    /**
     * Set allowExternalInvoice.
     *
     * @param string|null $allowExternalInvoice
     *
     * @return Client
     */
    public function setAllowExternalInvoice($allowExternalInvoice = null)
    {
        $this->allowExternalInvoice = $allowExternalInvoice;

        return $this;
    }

    /**
     * Get allowExternalInvoice.
     *
     * @return string|null
     */
    public function getAllowExternalInvoice()
    {
        return $this->allowExternalInvoice;
    }

    /**
     * Set allowVoucher.
     *
     * @param string|null $allowVoucher
     *
     * @return Client
     */
    public function setAllowVoucher($allowVoucher = null)
    {
        $this->allowVoucher = $allowVoucher;

        return $this;
    }

    /**
     * Get allowVoucher.
     *
     * @return string|null
     */
    public function getAllowVoucher()
    {
        return $this->allowVoucher;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Client
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set cardMonthlyLimit.
     *
     * @param string|null $cardMonthlyLimit
     *
     * @return Client
     */
    public function setCardMonthlyLimit($cardMonthlyLimit = null)
    {
        $this->cardMonthlyLimit = $cardMonthlyLimit;

        return $this;
    }

    /**
     * Get cardMonthlyLimit.
     *
     * @return string|null
     */
    public function getCardMonthlyLimit()
    {
        return $this->cardMonthlyLimit;
    }

    /**
     * Set cardDailyLimit.
     *
     * @param string|null $cardDailyLimit
     *
     * @return Client
     */
    public function setCardDailyLimit($cardDailyLimit = null)
    {
        $this->cardDailyLimit = $cardDailyLimit;

        return $this;
    }

    /**
     * Get cardDailyLimit.
     *
     * @return string|null
     */
    public function getCardDailyLimit()
    {
        return $this->cardDailyLimit;
    }

    /**
     * Set cardDailyUsage.
     *
     * @param int|null $cardDailyUsage
     *
     * @return Client
     */
    public function setCardDailyUsage($cardDailyUsage = null)
    {
        $this->cardDailyUsage = $cardDailyUsage;

        return $this;
    }

    /**
     * Get cardDailyUsage.
     *
     * @return int|null
     */
    public function getCardDailyUsage()
    {
        return $this->cardDailyUsage;
    }

    /**
     * Set cardWorkingDays.
     *
     * @param string|null $cardWorkingDays
     *
     * @return Client
     */
    public function setCardWorkingDays($cardWorkingDays = null)
    {
        $this->cardWorkingDays = $cardWorkingDays;

        return $this;
    }

    /**
     * Get cardWorkingDays.
     *
     * @return string|null
     */
    public function getCardWorkingDays()
    {
        return $this->cardWorkingDays;
    }

    /**
     * Set voucherMonthlyLimit.
     *
     * @param string|null $voucherMonthlyLimit
     *
     * @return Client
     */
    public function setVoucherMonthlyLimit($voucherMonthlyLimit = null)
    {
        $this->voucherMonthlyLimit = $voucherMonthlyLimit;

        return $this;
    }

    /**
     * Get voucherMonthlyLimit.
     *
     * @return string|null
     */
    public function getVoucherMonthlyLimit()
    {
        return $this->voucherMonthlyLimit;
    }

    /**
     * Set voucherLimit.
     *
     * @param string|null $voucherLimit
     *
     * @return Client
     */
    public function setVoucherLimit($voucherLimit = null)
    {
        $this->voucherLimit = $voucherLimit;

        return $this;
    }

    /**
     * Get voucherLimit.
     *
     * @return string|null
     */
    public function getVoucherLimit()
    {
        return $this->voucherLimit;
    }

    /**
     * Set voucherMaxCount.
     *
     * @param int|null $voucherMaxCount
     *
     * @return Client
     */
    public function setVoucherMaxCount($voucherMaxCount = null)
    {
        $this->voucherMaxCount = $voucherMaxCount;

        return $this;
    }

    /**
     * Get voucherMaxCount.
     *
     * @return int|null
     */
    public function getVoucherMaxCount()
    {
        return $this->voucherMaxCount;
    }

    /**
     * Set addressStreet.
     *
     * @param string|null $addressStreet
     *
     * @return Client
     */
    public function setAddressStreet($addressStreet = null)
    {
        $this->addressStreet = $addressStreet;

        return $this;
    }

    /**
     * Get addressStreet.
     *
     * @return string|null
     */
    public function getAddressStreet()
    {
        return $this->addressStreet;
    }

    /**
     * Set addressPostalCode.
     *
     * @param string|null $addressPostalCode
     *
     * @return Client
     */
    public function setAddressPostalCode($addressPostalCode = null)
    {
        $this->addressPostalCode = $addressPostalCode;

        return $this;
    }

    /**
     * Get addressPostalCode.
     *
     * @return string|null
     */
    public function getAddressPostalCode()
    {
        return $this->addressPostalCode;
    }

    /**
     * Set addressTown.
     *
     * @param string|null $addressTown
     *
     * @return Client
     */
    public function setAddressTown($addressTown = null)
    {
        $this->addressTown = $addressTown;

        return $this;
    }

    /**
     * Get addressTown.
     *
     * @return string|null
     */
    public function getAddressTown()
    {
        return $this->addressTown;
    }

    /**
     * Set addressCountry.
     *
     * @param string|null $addressCountry
     *
     * @return Client
     */
    public function setAddressCountry($addressCountry = null)
    {
        $this->addressCountry = $addressCountry;

        return $this;
    }

    /**
     * Get addressCountry.
     *
     * @return string|null
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }

    /**
     * Set mailingAddressStreet.
     *
     * @param string|null $mailingAddressStreet
     *
     * @return Client
     */
    public function setMailingAddressStreet($mailingAddressStreet = null)
    {
        $this->mailingAddressStreet = $mailingAddressStreet;

        return $this;
    }

    /**
     * Get mailingAddressStreet.
     *
     * @return string|null
     */
    public function getMailingAddressStreet()
    {
        return $this->mailingAddressStreet;
    }

    /**
     * Set mailingAddressPostalCode.
     *
     * @param string|null $mailingAddressPostalCode
     *
     * @return Client
     */
    public function setMailingAddressPostalCode($mailingAddressPostalCode = null)
    {
        $this->mailingAddressPostalCode = $mailingAddressPostalCode;

        return $this;
    }

    /**
     * Get mailingAddressPostalCode.
     *
     * @return string|null
     */
    public function getMailingAddressPostalCode()
    {
        return $this->mailingAddressPostalCode;
    }

    /**
     * Set mailingAddressTown.
     *
     * @param string|null $mailingAddressTown
     *
     * @return Client
     */
    public function setMailingAddressTown($mailingAddressTown = null)
    {
        $this->mailingAddressTown = $mailingAddressTown;

        return $this;
    }

    /**
     * Get mailingAddressTown.
     *
     * @return string|null
     */
    public function getMailingAddressTown()
    {
        return $this->mailingAddressTown;
    }

    /**
     * Set mailingAddressCountry.
     *
     * @param string|null $mailingAddressCountry
     *
     * @return Client
     */
    public function setMailingAddressCountry($mailingAddressCountry = null)
    {
        $this->mailingAddressCountry = $mailingAddressCountry;

        return $this;
    }

    /**
     * Get mailingAddressCountry.
     *
     * @return string|null
     */
    public function getMailingAddressCountry()
    {
        return $this->mailingAddressCountry;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Client
     */
    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set altPhoneNumber.
     *
     * @param string|null $altPhoneNumber
     *
     * @return Client
     */
    public function setAltPhoneNumber($altPhoneNumber = null)
    {
        $this->altPhoneNumber = $altPhoneNumber;

        return $this;
    }

    /**
     * Get altPhoneNumber.
     *
     * @return string|null
     */
    public function getAltPhoneNumber()
    {
        return $this->altPhoneNumber;
    }

    /**
     * Set sequenceId.
     *
     * @param int $sequenceId
     *
     * @return Client
     */
    public function setSequenceId($sequenceId)
    {
        $this->sequenceId = $sequenceId;

        return $this;
    }

    /**
     * Get sequenceId.
     *
     * @return int
     */
    public function getSequenceId()
    {
        return $this->sequenceId;
    }

    /**
     * Set agreementNumber.
     *
     * @param string|null $agreementNumber
     *
     * @return Client
     */
    public function setAgreementNumber($agreementNumber = null)
    {
        $this->agreementNumber = $agreementNumber;

        return $this;
    }

    /**
     * Get agreementNumber.
     *
     * @return string|null
     */
    public function getAgreementNumber()
    {
        return $this->agreementNumber;
    }

    /**
     * Set agreementUntil.
     *
     * @param \DateTime|null $agreementUntil
     *
     * @return Client
     */
    public function setAgreementUntil($agreementUntil = null)
    {
        $this->agreementUntil = $agreementUntil;

        return $this;
    }

    /**
     * Get agreementUntil.
     *
     * @return \DateTime|null
     */
    public function getAgreementUntil()
    {
        return $this->agreementUntil;
    }

    /**
     * Set paymentDays.
     *
     * @param int|null $paymentDays
     *
     * @return Client
     */
    public function setPaymentDays($paymentDays = null)
    {
        $this->paymentDays = $paymentDays;

        return $this;
    }

    /**
     * Get paymentDays.
     *
     * @return int|null
     */
    public function getPaymentDays()
    {
        return $this->paymentDays;
    }

    /**
     * Set discount.
     *
     * @param string|null $discount
     *
     * @return Client
     */
    public function setDiscount($discount = null)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount.
     *
     * @return string|null
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set cardProvision.
     *
     * @param string $cardProvision
     *
     * @return Client
     */
    public function setCardProvision($cardProvision)
    {
        $this->cardProvision = $cardProvision;

        return $this;
    }

    /**
     * Get cardProvision.
     *
     * @return string
     */
    public function getCardProvision()
    {
        return $this->cardProvision;
    }

    /**
     * Set eVoucherProvision.
     *
     * @param string $eVoucherProvision
     *
     * @return Client
     */
    public function setEVoucherProvision($eVoucherProvision)
    {
        $this->eVoucherProvision = $eVoucherProvision;

        return $this;
    }

    /**
     * Get eVoucherProvision.
     *
     * @return string
     */
    public function getEVoucherProvision()
    {
        return $this->eVoucherProvision;
    }

    /**
     * Set voucherProvision.
     *
     * @param string $voucherProvision
     *
     * @return Client
     */
    public function setVoucherProvision($voucherProvision)
    {
        $this->voucherProvision = $voucherProvision;

        return $this;
    }

    /**
     * Get voucherProvision.
     *
     * @return string
     */
    public function getVoucherProvision()
    {
        return $this->voucherProvision;
    }

    /**
     * Set invoiceName.
     *
     * @param string|null $invoiceName
     *
     * @return Client
     */
    public function setInvoiceName($invoiceName = null)
    {
        $this->invoiceName = $invoiceName;

        return $this;
    }

    /**
     * Get invoiceName.
     *
     * @return string|null
     */
    public function getInvoiceName()
    {
        return $this->invoiceName;
    }

    /**
     * Set invoiceSign.
     *
     * @param string|null $invoiceSign
     *
     * @return Client
     */
    public function setInvoiceSign($invoiceSign = null)
    {
        $this->invoiceSign = $invoiceSign;

        return $this;
    }

    /**
     * Get invoiceSign.
     *
     * @return string|null
     */
    public function getInvoiceSign()
    {
        return $this->invoiceSign;
    }

    /**
     * Set invoiceEmail.
     *
     * @param string|null $invoiceEmail
     *
     * @return Client
     */
    public function setInvoiceEmail($invoiceEmail = null)
    {
        $this->invoiceEmail = $invoiceEmail;

        return $this;
    }

    /**
     * Get invoiceEmail.
     *
     * @return string|null
     */
    public function getInvoiceEmail()
    {
        return $this->invoiceEmail;
    }

    /**
     * Set envelopeSign.
     *
     * @param string|null $envelopeSign
     *
     * @return Client
     */
    public function setEnvelopeSign($envelopeSign = null)
    {
        $this->envelopeSign = $envelopeSign;

        return $this;
    }

    /**
     * Get envelopeSign.
     *
     * @return string|null
     */
    public function getEnvelopeSign()
    {
        return $this->envelopeSign;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Client
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Client
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
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Client
     */
    public function setUser(User $user = null)
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
    public function getOwnProvision()
    {
        return $this->ownProvision;
    }

    /**
     * @param mixed $ownProvision
     * @return Client
     */
    public function setOwnProvision($ownProvision)
    {
        $this->ownProvision = $ownProvision;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSkipInvoice()
    {
        return $this->skipInvoice;
    }

    /**
     * @param mixed $skipInvoice
     * @return Client
     */
    public function setSkipInvoice($skipInvoice)
    {
        $this->skipInvoice = $skipInvoice;
        return $this;
    }



    public function __toString()
    {
        return $this->agreementNumber . ': '. $this->name;
    }

    /**
     * Add card.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Card $card
     *
     * @return Client
     */
    public function addCard(\AppBundle\Entity\ApiTaxi360\Card $card)
    {
        $card->setClient($this);
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Card $card
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCard(\AppBundle\Entity\ApiTaxi360\Card $card)
    {
        $card->setClient(null);
        return $this->cards->removeElement($card);
    }

    /**
     * Get cards.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCards()
    {
        return $this->cards;
    }
}

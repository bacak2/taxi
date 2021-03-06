<?php

namespace AppBundle\Entity\ApiTaxi360;

use AppBundle\Service\HelperService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DriverRepository")
 * @ORM\Table(name="driver")
 * @ORM\HasLifecycleCallbacks()
 */
class Driver implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="taxi_driver_id", nullable=true)
     */
    private $taxiDriverId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $action;

    /**
     * @ORM\Column(type="datetime", name="movement_date", nullable=true)
     */
    private $movementDate;

    /**
     * @ORM\Column(type="string", name="license_number", nullable=true)
     */
    private $licenseNumber;

    /**
     * @ORM\Column(type="string", name="first_name", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="mobile_number", nullable=true)
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", name="phone_number", nullable=true)
     */
    private $phoneNumber;

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
    private $addressCountry = 'Polska';

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
    private $mailingAddressCountry = 'Polska';

    /**
     * @ORM\Column(type="string", name="account_number", nullable=true)
     */
    private $accountNumber;

    /**
     * @ORM\Column(type="string", name="account_owner", nullable=true)
     */
    private $accountOwner;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $prepaid;

    /**
     * @ORM\Column(type="string", name="cab_side_number", nullable=true)
     */
    private $cabSideNumber;

    /**
     * @ORM\Column(type="string",name="cab_registration_number", nullable=true)
     */
    private $cabRegistrationNumber;

    /**
     * @ORM\Column(type="integer", name="taxiterminal_id", nullable=true)
     */
    private $taxiterminalId;

    /**
     * @ORM\Column(type="string", name="terminal_tid", nullable=true)
     */
    private $terminalTid;

    /**
     * @ORM\Column(type="string", name="terminal_model", nullable=true)
     */
    private $terminalModel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * ACTIVE | RETIRED | BLOCKED
     */
    private $status;

    /**
     * @ORM\Column(type="string", name="is_employee", nullable=true)
     * CORPO | DRIVER
     */
    private $isEmployee;

    /**
     * @ORM\Column(type="string", name="invoice_type", nullable=true)
     * INVOICE | RECEIPT
     */
    private $invoiceType;

    /**
     * @ORM\Column(type="string", name="firm_name" ,nullable=true)
     */
    private $firmName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $nip;

    /**
     * @ORM\Column(type="string", name="allow_external_invoice", nullable=true)
     */
    private $allowExternalInvoice;

    /**
     * @ORM\Column(type="decimal", scale=2, name="invoice_goods_reference_vat", nullable=true)
     */
    private $invoiceGoodsReferenceVat;

    /**
     * @ORM\Column(type="string", name="invoice_goods_reference_description", nullable=true)
     */
    private $invoiceGoodsReferenceDescription;

    /**
     * @ORM\Column(type="integer", name="sequence_id")
     */
    private $sequenceId;

    /*****************************************************************************/
    /**
     * @ORM\Column(type="string", name="pos_number_mid", nullable=true)
     */
    private $posNumberMid;

    /**
     * @ORM\Column(type="boolean", name="vat_payer")
     */
    private $vatPayer = false;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="boolean", name="is_baggage")
     */
    private $isBaggage = false;

    /**
     * @ORM\Column(type="boolean", name="free_money_transfer")
     */
    private $freeMoneyTransfer = false;

    /**
     * @ORM\Column(type="boolean", name="invoice_for_provision")
     */
    private $invoiceForProvision = false;

    /**
     * @ORM\Column(type="boolean", name="periodic_transfer")
     */
    private $periodicTransfer = true;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Blockade",
     *     mappedBy="driver",
     *     cascade={"persist"}
     * )
     */
    private $blockade;

    /**
     * @ORM\Column(type="boolean")
     */
    private $blocked = false;

    /**
     * @ORM\Column(type="string", name="internet_password", nullable=true)
     */
    private $internetPassword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="datetime", name="deleted_date", nullable=true)
     */
    private $deletedDate;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

    /**
     * Driver constructor.
     */
    public function __construct()
    {
        $this->blockade = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function updateDate()
    {
        $this->updateDate = new \DateTime();
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Driver
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set taxiDriverId.
     *
     * @param int|null $taxiDriverId
     *
     * @return Driver
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
     * Set action.
     *
     * @param string|null $action
     *
     * @return Driver
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
     * @param \DateTime|null $movementDate
     *
     * @return Driver
     */
    public function setMovementDate($movementDate = null)
    {
        $this->movementDate = $movementDate;

        return $this;
    }

    /**
     * Get movementDate.
     *
     * @return \DateTime|null
     */
    public function getMovementDate()
    {
        return $this->movementDate;
    }

    /**
     * Set licenseNumber.
     *
     * @param string|null $licenseNumber
     *
     * @return Driver
     */
    public function setLicenseNumber($licenseNumber = null)
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    /**
     * Get licenseNumber.
     *
     * @return string|null
     */
    public function getLicenseNumber()
    {
        return $this->licenseNumber;
    }

    /**
     * Set firstName.
     *
     * @param string|null $firstName
     *
     * @return Driver
     */
    public function setFirstName($firstName = null)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set surname.
     *
     * @param string|null $surname
     *
     * @return Driver
     */
    public function setSurname($surname = null)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname.
     *
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Driver
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
     * Set mobileNumber.
     *
     * @param string|null $mobileNumber
     *
     * @return Driver
     */
    public function setMobileNumber($mobileNumber = null)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * Get mobileNumber.
     *
     * @return string|null
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Driver
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
     * Set addressStreet.
     *
     * @param string|null $addressStreet
     *
     * @return Driver
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
     * @return Driver
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
     * @return Driver
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
     * @return Driver
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
     * @return Driver
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
     * @return Driver
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
     * @return Driver
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
     * @return Driver
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
     * Set accountNumber.
     *
     * @param string|null $accountNumber
     *
     * @return Driver
     */
    public function setAccountNumber($accountNumber = null)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber.
     *
     * @return string|null
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set accountOwner.
     *
     * @param string|null $accountOwner
     *
     * @return Driver
     */
    public function setAccountOwner($accountOwner = null)
    {
        $this->accountOwner = $accountOwner;

        return $this;
    }

    /**
     * Get accountOwner.
     *
     * @return string|null
     */
    public function getAccountOwner()
    {
        return $this->accountOwner;
    }

    /**
     * Set prepaid.
     *
     * @param string|null $prepaid
     *
     * @return Driver
     */
    public function setPrepaid($prepaid = null)
    {
        $this->prepaid = $prepaid;

        return $this;
    }

    /**
     * Get prepaid.
     *
     * @return string|null
     */
    public function getPrepaid()
    {
        return $this->prepaid;
    }

    /**
     * Set cabSideNumber.
     *
     * @param string|null $cabSideNumber
     *
     * @return Driver
     */
    public function setCabSideNumber($cabSideNumber = null)
    {
        $this->cabSideNumber = $cabSideNumber;

        return $this;
    }

    /**
     * Get cabSideNumber.
     *
     * @return string|null
     */
    public function getCabSideNumber()
    {
        return $this->cabSideNumber;
    }

    /**
     * Set cabRegistrationNumber.
     *
     * @param string|null $cabRegistrationNumber
     *
     * @return Driver
     */
    public function setCabRegistrationNumber($cabRegistrationNumber = null)
    {
        $this->cabRegistrationNumber = $cabRegistrationNumber;

        return $this;
    }

    /**
     * Get cabRegistrationNumber.
     *
     * @return string|null
     */
    public function getCabRegistrationNumber()
    {
        return $this->cabRegistrationNumber;
    }

    /**
     * Set taxiterminalId.
     *
     * @param int|null $taxiterminalId
     *
     * @return Driver
     */
    public function setTaxiterminalId($taxiterminalId = null)
    {
        $this->taxiterminalId = $taxiterminalId;

        return $this;
    }

    /**
     * Get taxiterminalId.
     *
     * @return int|null
     */
    public function getTaxiterminalId()
    {
        return $this->taxiterminalId;
    }

    /**
     * Set terminalTid.
     *
     * @param string|null $terminalTid
     *
     * @return Driver
     */
    public function setTerminalTid($terminalTid = null)
    {
        $this->terminalTid = $terminalTid;

        return $this;
    }

    /**
     * Get terminalTid.
     *
     * @return string|null
     */
    public function getTerminalTid()
    {
        return $this->terminalTid;
    }

    /**
     * Set terminalModel.
     *
     * @param string|null $terminalModel
     *
     * @return Driver
     */
    public function setTerminalModel($terminalModel = null)
    {
        $this->terminalModel = $terminalModel;

        return $this;
    }

    /**
     * Get terminalModel.
     *
     * @return string|null
     */
    public function getTerminalModel()
    {
        return $this->terminalModel;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Driver
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
     * Set isEmployee.
     *
     * @param string|null $isEmployee
     *
     * @return Driver
     */
    public function setIsEmployee($isEmployee = null)
    {
        $this->isEmployee = $isEmployee;

        return $this;
    }

    /**
     * Get isEmployee.
     *
     * @return string|null
     */
    public function getIsEmployee()
    {
        return $this->isEmployee;
    }

    /**
     * Set invoiceType.
     *
     * @param string|null $invoiceType
     *
     * @return Driver
     */
    public function setInvoiceType($invoiceType = null)
    {
        $this->invoiceType = $invoiceType;

        return $this;
    }

    /**
     * Get invoiceType.
     *
     * @return string|null
     */
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    /**
     * Set firmName.
     *
     * @param string|null $firmName
     *
     * @return Driver
     */
    public function setFirmName($firmName = null)
    {
        $this->firmName = $firmName;

        return $this;
    }

    /**
     * Get firmName.
     *
     * @return string|null
     */
    public function getFirmName()
    {
        return $this->firmName;
    }

    /**
     * Set nip.
     *
     * @param string|null $nip
     *
     * @return Driver
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
     * Set allowExternalInvoice.
     *
     * @param string|null $allowExternalInvoice
     *
     * @return Driver
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
     * Set invoiceGoodsReferenceVat.
     *
     * @param string|null $invoiceGoodsReferenceVat
     *
     * @return Driver
     */
    public function setInvoiceGoodsReferenceVat($invoiceGoodsReferenceVat = null)
    {
        $this->invoiceGoodsReferenceVat = $invoiceGoodsReferenceVat;

        return $this;
    }

    /**
     * Get invoiceGoodsReferenceVat.
     *
     * @return string|null
     */
    public function getInvoiceGoodsReferenceVat()
    {
        return $this->invoiceGoodsReferenceVat;
    }

    /**
     * Set invoiceGoodsReferenceDescription.
     *
     * @param string|null $invoiceGoodsReferenceDescription
     *
     * @return Driver
     */
    public function setInvoiceGoodsReferenceDescription($invoiceGoodsReferenceDescription = null)
    {
        $this->invoiceGoodsReferenceDescription = $invoiceGoodsReferenceDescription;

        return $this;
    }

    /**
     * Get invoiceGoodsReferenceDescription.
     *
     * @return string|null
     */
    public function getInvoiceGoodsReferenceDescription()
    {
        return $this->invoiceGoodsReferenceDescription;
    }

    /**
     * Set sequenceId.
     *
     * @param int $sequenceId
     *
     * @return Driver
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
     * Set vatPayer.
     *
     * @param bool $vatPayer
     *
     * @return Driver
     */
    public function setVatPayer($vatPayer)
    {
        $this->vatPayer = $vatPayer;

        return $this;
    }

    /**
     * Get vatPayer.
     *
     * @return bool
     */
    public function getVatPayer()
    {
        return $this->vatPayer;
    }

    /**
     * Set vat.
     *
     * @param string|null $vat
     *
     * @return Driver
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
     * Set isBaggage.
     *
     * @param bool $isBaggage
     *
     * @return Driver
     */
    public function setIsBaggage($isBaggage)
    {
        $this->isBaggage = $isBaggage;

        return $this;
    }

    /**
     * Get isBaggage.
     *
     * @return bool
     */
    public function getIsBaggage()
    {
        return $this->isBaggage;
    }

    /**
     * Set freeMoneyTransfer.
     *
     * @param bool $freeMoneyTransfer
     *
     * @return Driver
     */
    public function setFreeMoneyTransfer($freeMoneyTransfer)
    {
        $this->freeMoneyTransfer = $freeMoneyTransfer;

        return $this;
    }

    /**
     * Get freeMoneyTransfer.
     *
     * @return bool
     */
    public function getFreeMoneyTransfer()
    {
        return $this->freeMoneyTransfer;
    }

    /**
     * Set invoiceForProvision.
     *
     * @param bool $invoiceForProvision
     *
     * @return Driver
     */
    public function setInvoiceForProvision($invoiceForProvision)
    {
        $this->invoiceForProvision = $invoiceForProvision;

        return $this;
    }

    /**
     * Get invoiceForProvision.
     *
     * @return bool
     */
    public function getInvoiceForProvision()
    {
        return $this->invoiceForProvision;
    }

    /**
     * Set periodicTransfer.
     *
     * @param bool $periodicTransfer
     *
     * @return Driver
     */
    public function setPeriodicTransfer($periodicTransfer)
    {
        $this->periodicTransfer = $periodicTransfer;

        return $this;
    }

    /**
     * Get periodicTransfer.
     *
     * @return bool
     */
    public function getPeriodicTransfer()
    {
        return $this->periodicTransfer;
    }

    /**
     * Set blocked.
     *
     * @param bool $blocked
     *
     * @return Driver
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * Get blocked.
     *
     * @return bool
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Set internetPassword.
     *
     * @param string|null $internetPassword
     *
     * @return Driver
     */
    public function setInternetPassword($internetPassword = null)
    {
        $this->internetPassword = $internetPassword;

        return $this;
    }

    /**
     * Get internetPassword.
     *
     * @return string|null
     */
    public function getInternetPassword()
    {
        return $this->internetPassword;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Driver
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
     * Set deleted.
     *
     * @param bool $deleted
     *
     * @return Driver
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deletedDate.
     *
     * @param \DateTime|null $deletedDate
     *
     * @return Driver
     */
    public function setDeletedDate($deletedDate = null)
    {
        $this->deletedDate = $deletedDate;

        return $this;
    }

    /**
     * Get deletedDate.
     *
     * @return \DateTime|null
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Driver
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
     * @return Driver
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
     * Add blockade.
     *
     * @param \AppBundle\Entity\Blockade $blockade
     *
     * @return Driver
     */
    public function addBlockade(\AppBundle\Entity\Blockade $blockade)
    {
        $blockade->setDriver($this);
        $this->blockade[] = $blockade;

        return $this;
    }

    /**
     * Remove blockade.
     *
     * @param \AppBundle\Entity\Blockade $blockade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBlockade(\AppBundle\Entity\Blockade $blockade)
    {
        $blockade->setDriver(null);
        return $this->blockade->removeElement($blockade);
    }

    /**
     * Get blockade.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlockade()
    {
        return $this->blockade;
    }

    public function __toString()
    {
        return $this->licenseNumber . ': '. $this->firstName . ' ' . $this->surname;
    }

    public function serialize()
    {
        serialize([
            $this->id,
            $this->cabSideNumber,
            $this->internetPassword
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->cabSideNumber,
            $this->internetPassword
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        return ['ROLE_DRIVER'];
    }

    public function getPassword()
    {
        return $this->internetPassword;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->cabSideNumber;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getPosNumberMid()
    {
        return $this->posNumberMid;
    }

    /**
     * @param mixed $posNumberMid
     * @return Driver
     */
    public function setPosNumberMid($posNumberMid)
    {
        $this->posNumberMid = $posNumberMid;
        return $this;
    }


}

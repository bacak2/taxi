<?php

namespace AppBundle\Entity\Params;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="param_app")
 * @ORM\HasLifecycleCallbacks()
 */
class TaxiSettings
{
    /**
     * @ORM\Id
     * @ORM\Column(type="smallint")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=3, name="american_express", nullable=true)
     */
    private $americanExpress = 0;

    /**
     * @ORM\Column(type="decimal", scale=3, name="visa_master_card", nullable=true)
     */
    private $visaMasterCard = 0;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $card = 0;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $voucher = 0;

    /**
     * @ORM\Column(type="decimal", scale=3, name="evoucher", nullable=true)
     */
    private $eVoucher = 0;

    /**
     * @ORM\Column(type="decimal", scale=2, name="bank_transfer_cost", nullable=true)
     */
    private $bankTransferCost = 0;

    /**
     * @ORM\Column(type="string", name="bank_name", nullable=true)
     */
    private $bankName;

    /**
     * @ORM\Column(type="string", name="bank_account", nullable=true)
     */
    private $bankAccount;

    /**
     * @ORM\Column(type="string", name="bank_account_to_firm", nullable=true)
     */
    private $bankAccountToFirm;

    /**
     * @ORM\Column(type="string", name="free_transfer_bank_account", nullable=true)
     */
    private $freeTransferBankAccount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $swift;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="integer", name="days_to_pay", nullable=true)
     */
    private $daysToPay;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

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
     * @param mixed $id
     * @return TaxiSettings
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set americanExpress.
     *
     * @param string $americanExpress
     *
     * @return TaxiSettings
     */
    public function setAmericanExpress($americanExpress)
    {
        $this->americanExpress = $americanExpress;

        return $this;
    }

    /**
     * Get americanExpress.
     *
     * @return string
     */
    public function getAmericanExpress()
    {
        return $this->americanExpress;
    }

    /**
     * Set visaMasterCard.
     *
     * @param string $visaMasterCard
     *
     * @return TaxiSettings
     */
    public function setVisaMasterCard($visaMasterCard)
    {
        $this->visaMasterCard = $visaMasterCard;

        return $this;
    }

    /**
     * Get visaMasterCard.
     *
     * @return string
     */
    public function getVisaMasterCard()
    {
        return $this->visaMasterCard;
    }

    /**
     * Set card.
     *
     * @param string $card
     *
     * @return TaxiSettings
     */
    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card.
     *
     * @return string
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set voucher.
     *
     * @param string $voucher
     *
     * @return TaxiSettings
     */
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;

        return $this;
    }

    /**
     * Get voucher.
     *
     * @return string
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * Set eVoucher.
     *
     * @param string $eVoucher
     *
     * @return TaxiSettings
     */
    public function setEVoucher($eVoucher)
    {
        $this->eVoucher = $eVoucher;

        return $this;
    }

    /**
     * Get eVoucher.
     *
     * @return string
     */
    public function getEVoucher()
    {
        return $this->eVoucher;
    }

    /**
     * Set bankTransferCost.
     *
     * @param string $bankTransferCost
     *
     * @return TaxiSettings
     */
    public function setBankTransferCost($bankTransferCost)
    {
        $this->bankTransferCost = $bankTransferCost;

        return $this;
    }

    /**
     * Get bankTransferCost.
     *
     * @return string
     */
    public function getBankTransferCost()
    {
        return $this->bankTransferCost;
    }

    /**
     * Set bankAccount.
     *
     * @param string|null $bankAccount
     *
     * @return TaxiSettings
     */
    public function setBankAccount($bankAccount = null)
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount.
     *
     * @return string|null
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * Set bankAccountToFirm.
     *
     * @param string|null $bankAccountToFirm
     *
     * @return TaxiSettings
     */
    public function setBankAccountToFirm($bankAccountToFirm = null)
    {
        $this->bankAccountToFirm = $bankAccountToFirm;

        return $this;
    }

    /**
     * Get bankAccountToFirm.
     *
     * @return string|null
     */
    public function getBankAccountToFirm()
    {
        return $this->bankAccountToFirm;
    }

    /**
     * Set freeTransferBankAccount.
     *
     * @param string|null $freeTransferBankAccount
     *
     * @return TaxiSettings
     */
    public function setFreeTransferBankAccount($freeTransferBankAccount = null)
    {
        $this->freeTransferBankAccount = $freeTransferBankAccount;

        return $this;
    }

    /**
     * Get freeTransferBankAccount.
     *
     * @return string|null
     */
    public function getFreeTransferBankAccount()
    {
        return $this->freeTransferBankAccount;
    }

    /**
     * Set swift.
     *
     * @param string|null $swift
     *
     * @return TaxiSettings
     */
    public function setSwift($swift = null)
    {
        $this->swift = $swift;

        return $this;
    }

    /**
     * Get swift.
     *
     * @return string|null
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return TaxiSettings
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
     * @return mixed
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @param mixed $bankName
     * @return TaxiSettings
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param mixed $vat
     * @return TaxiSettings
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDaysToPay()
    {
        return $this->daysToPay;
    }

    /**
     * @param mixed $daysToPay
     * @return TaxiSettings
     */
    public function setDaysToPay($daysToPay)
    {
        $this->daysToPay = $daysToPay;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->updateDate = new \DateTime();
    }
}

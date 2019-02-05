<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\ArrayCollection;

class SettingsFormData
{
    private $americanExpress;

    private $visaMasterCard;

    private $card;

    private $voucher;

    private $eVoucher;

    private $bankTransferCost;

    private $bankName;

    private $bankAccount;

    private $bankAccountToFirm;

    private $swift;

    private $freeTransferBankAccount;

    private $vat;

    private $daysToPay;

    private $params;

    /**
     * @return mixed
     */
    public function getAmericanExpress()
    {
        return $this->americanExpress;
    }

    /**
     * @param mixed $americanExpress
     * @return SettingsFormData
     */
    public function setAmericanExpress($americanExpress)
    {
        $this->americanExpress = $americanExpress;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVisaMasterCard()
    {
        return $this->visaMasterCard;
    }

    /**
     * @param mixed $visaMasterCard
     * @return SettingsFormData
     */
    public function setVisaMasterCard($visaMasterCard)
    {
        $this->visaMasterCard = $visaMasterCard;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param mixed $card
     * @return SettingsFormData
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    /**
     * @param mixed $voucher
     * @return SettingsFormData
     */
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEVoucher()
    {
        return $this->eVoucher;
    }

    /**
     * @param mixed $eVoucher
     * @return SettingsFormData
     */
    public function setEVoucher($eVoucher)
    {
        $this->eVoucher = $eVoucher;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankTransferCost()
    {
        return $this->bankTransferCost;
    }

    /**
     * @param mixed $bankTransferCost
     * @return SettingsFormData
     */
    public function setBankTransferCost($bankTransferCost)
    {
        $this->bankTransferCost = $bankTransferCost;
        return $this;
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
     * @return SettingsFormData
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param mixed $bankAccount
     * @return SettingsFormData
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankAccountToFirm()
    {
        return $this->bankAccountToFirm;
    }

    /**
     * @param mixed $bankAccountToFirm
     * @return SettingsFormData
     */
    public function setBankAccountToFirm($bankAccountToFirm)
    {
        $this->bankAccountToFirm = $bankAccountToFirm;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * @param mixed $swift
     * @return SettingsFormData
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFreeTransferBankAccount()
    {
        return $this->freeTransferBankAccount;
    }

    /**
     * @param mixed $freeTransferBankAccount
     * @return SettingsFormData
     */
    public function setFreeTransferBankAccount($freeTransferBankAccount)
    {
        $this->freeTransferBankAccount = $freeTransferBankAccount;
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
     * @return SettingsFormData
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
     * @return SettingsFormData
     */
    public function setDaysToPay($daysToPay)
    {
        $this->daysToPay = $daysToPay;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param ArrayCollection $params
     * @return SettingsFormData
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
}
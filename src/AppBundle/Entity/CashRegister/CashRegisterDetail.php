<?php

namespace AppBundle\Entity\CashRegister;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CashRegisterDetailRepository")
 * @ORM\Table(name="cash_register_detail")
 */
class CashRegisterDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\CashRegister\CashRegister",
     *     inversedBy="cashRegisterDetails"
     * )
     * @ORM\JoinColumn(
     *     name="cash_register_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $cashRegister;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Params\Param"
     * )
     * @ORM\JoinColumn(
     *     name="param_id",
     *     referencedColumnName="id"
     * )
     */
    private $param;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $netto;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $brutto;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

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
     * Set netto.
     *
     * @param string|null $netto
     *
     * @return CashRegisterDetail
     */
    public function setNetto($netto = null)
    {
        $this->netto = $netto;

        return $this;
    }

    /**
     * Get netto.
     *
     * @return string|null
     */
    public function getNetto()
    {
        return $this->netto;
    }

    /**
     * Set vat.
     *
     * @param string|null $vat
     *
     * @return CashRegisterDetail
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
     * Set brutto.
     *
     * @param string|null $brutto
     *
     * @return CashRegisterDetail
     */
    public function setBrutto($brutto = null)
    {
        $this->brutto = $brutto;

        return $this;
    }

    /**
     * Get brutto.
     *
     * @return string|null
     */
    public function getBrutto()
    {
        return $this->brutto;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return CashRegisterDetail
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set cashRegister.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegister|null $cashRegister
     *
     * @return CashRegisterDetail
     */
    public function setCashRegister(\AppBundle\Entity\CashRegister\CashRegister $cashRegister = null)
    {
        $this->cashRegister = $cashRegister;

        return $this;
    }

    /**
     * Get cashRegister.
     *
     * @return \AppBundle\Entity\CashRegister\CashRegister|null
     */
    public function getCashRegister()
    {
        return $this->cashRegister;
    }

    /**
     * Set param.
     *
     * @param \AppBundle\Entity\Params\Param|null $param
     *
     * @return CashRegisterDetail
     */
    public function setParam(\AppBundle\Entity\Params\Param $param = null)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get param.
     *
     * @return \AppBundle\Entity\Params\Param|null
     */
    public function getParam()
    {
        return $this->param;
    }
}

<?php

namespace AppBundle\Entity\Invoice;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="invoice_detail")
 */
class InvoiceDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Invoice\Invoice",
     *     inversedBy="invoiceDetail"
     * )
     * @ORM\JoinColumn(
     *     name="invoice_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $invoice;

    /**
     * @ORM\Column(type="integer")
     */
    private $lp;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="decimal", scale=2, name="amount_netto")
     */
    private $amountNetto;

    /**
     * @ORM\Column(type="decimal", scale=2, name="amount_brutto")
     */
    private $amountBrutto;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", scale=3)
     */
    private $vat;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $discount;

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
     * Set lp.
     *
     * @param int $lp
     *
     * @return InvoiceDetail
     */
    public function setLp($lp)
    {
        $this->lp = $lp;

        return $this;
    }

    /**
     * Get lp.
     *
     * @return int
     */
    public function getLp()
    {
        return $this->lp;
    }

    /**
     * Set title.
     *
     * @param string|null $title
     *
     * @return InvoiceDetail
     */
    public function setTitle($title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set amountNetto.
     *
     * @param string $amountNetto
     *
     * @return InvoiceDetail
     */
    public function setAmountNetto($amountNetto)
    {
        $this->amountNetto = $amountNetto;

        return $this;
    }

    /**
     * Get amountNetto.
     *
     * @return string
     */
    public function getAmountNetto()
    {
        return $this->amountNetto;
    }

    /**
     * Set amountBrutto.
     *
     * @param string $amountBrutto
     *
     * @return InvoiceDetail
     */
    public function setAmountBrutto($amountBrutto)
    {
        $this->amountBrutto = $amountBrutto;

        return $this;
    }

    /**
     * Get amountBrutto.
     *
     * @return string
     */
    public function getAmountBrutto()
    {
        return $this->amountBrutto;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return InvoiceDetail
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
     * Set vat.
     *
     * @param string $vat
     *
     * @return InvoiceDetail
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat.
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set discount.
     *
     * @param string|null $discount
     *
     * @return InvoiceDetail
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
     * Set invoice.
     *
     * @param \AppBundle\Entity\Invoice\Invoice|null $invoice
     *
     * @return InvoiceDetail
     */
    public function setInvoice(\AppBundle\Entity\Invoice\Invoice $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice.
     *
     * @return \AppBundle\Entity\Invoice\Invoice|null
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}

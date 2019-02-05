<?php

namespace AppBundle\Entity\Invoice;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 * @ORM\Table(name="invoice")
 */
class Invoice
{
    const TYPE_SALE = "Sprzedaż";
    const TYPE_BUY = "Kupno";
    const TYPE_CLIENT = "Client";
    const TYPE_DRIVER = "Driver";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Invoice\InvoiceDetail",
     *     mappedBy="invoice",
     *     cascade={"persist"}
     * )
     */
    private $invoiceDetail;

    /**
     * @ORM\Column(type="string")
     * SPRZEDAZ | KUPNO
     */
    private $transactionType;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Invoice\InvoiceFormat"
     * )
     * @ORM\JoinColumn(
     *     name="invoice_format_id",
     *     referencedColumnName="id"
     * )
     */
    private $invoiceFormat;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Invoice\InvoiceType"
     * )
     * @ORM\JoinColumn(
     *     name="invoice_type_id",
     *     referencedColumnName="id"
     * )
     */
    private $invoiceType;

    /**
     * @ORM\Column(type="integer", name="seller")
     */
    private $seller;

    /**
     * @ORM\Column(type="string", name="seller_type")
     */
    private $sellerType;

    /**
     * @ORM\Column(type="integer", name="buyer")
     */
    private $buyer;

    /**
     * @ORM\Column(type="string", name="buyer_type")
     */
    private $buyerType;

    /**
     * @ORM\Column(type="datetime", name="create_date")
     */
    private $createDate;

    /**
     * @ORM\Column(type="date", name="payment_date")
     */
    private $paymentDate;

    /**
     * @ORM\Column(type="string", name="invoice_number")
     */
    private $invoiceNumber;

    /**
     * @ORM\Column(type="smallint", name="invoice_month")
     */
    private $invoiceMonth;

    /**
     * @ORM\Column(type="integer", name="invoice_year")
     */
    private $invoiceYear;

    /**
     * @ORM\Column(type="decimal", scale=2, name="amount_brutto")
     */
    private $amountBrutto;

    /**
     * @ORM\Column(type="decimal", scale=2, name="amount_netto")
     */
    private $amountNetto;

    /**
     * @ORM\Column(type="decimal", scale=3)
     */
    private $vat;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Invoice\PaymentMethod"
     * )
     * @ORM\JoinColumn(
     *     name="payment_method_id",
     *     referencedColumnName="id",
     *     nullable=true
     * )
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $discount;

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
     * Constructor
     */
    public function __construct()
    {
        $this->invoiceDetail = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set transactionType.
     *
     * @param string $transactionType
     *
     * @return Invoice
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType.
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set seller.
     *
     * @param int $seller
     *
     * @return Invoice
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller.
     *
     * @return int
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set sellerType.
     *
     * @param string $sellerType
     *
     * @return Invoice
     */
    public function setSellerType($sellerType)
    {
        $this->sellerType = $sellerType;

        return $this;
    }

    /**
     * Get sellerType.
     *
     * @return string
     */
    public function getSellerType()
    {
        return $this->sellerType;
    }

    /**
     * Set buyer.
     *
     * @param int $buyer
     *
     * @return Invoice
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer.
     *
     * @return int
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set buyerType.
     *
     * @param string $buyerType
     *
     * @return Invoice
     */
    public function setBuyerType($buyerType)
    {
        $this->buyerType = $buyerType;

        return $this;
    }

    /**
     * Get buyerType.
     *
     * @return string
     */
    public function getBuyerType()
    {
        return $this->buyerType;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Invoice
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set paymentDate.
     *
     * @param \DateTime $paymentDate
     *
     * @return Invoice
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate.
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set invoiceNumber.
     *
     * @param string $invoiceNumber
     *
     * @return Invoice
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber.
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set invoiceMonth.
     *
     * @param int $invoiceMonth
     *
     * @return Invoice
     */
    public function setInvoiceMonth($invoiceMonth)
    {
        $this->invoiceMonth = $invoiceMonth;

        return $this;
    }

    /**
     * Get invoiceMonth.
     *
     * @return int
     */
    public function getInvoiceMonth()
    {
        return $this->invoiceMonth;
    }

    /**
     * Set invoiceYear.
     *
     * @param int $invoiceYear
     *
     * @return Invoice
     */
    public function setInvoiceYear($invoiceYear)
    {
        $this->invoiceYear = $invoiceYear;

        return $this;
    }

    /**
     * Get invoiceYear.
     *
     * @return int
     */
    public function getInvoiceYear()
    {
        return $this->invoiceYear;
    }

    /**
     * Set amountBrutto.
     *
     * @param string $amountBrutto
     *
     * @return Invoice
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
     * Set amountNetto.
     *
     * @param string $amountNetto
     *
     * @return Invoice
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
     * Set vat.
     *
     * @param string $vat
     *
     * @return Invoice
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
     * @return Invoice
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
     * Add invoiceDetail.
     *
     * @param \AppBundle\Entity\Invoice\InvoiceDetail $invoiceDetail
     *
     * @return Invoice
     */
    public function addInvoiceDetail(\AppBundle\Entity\Invoice\InvoiceDetail $invoiceDetail)
    {
        $this->invoiceDetail[] = $invoiceDetail;

        return $this;
    }

    /**
     * Remove invoiceDetail.
     *
     * @param \AppBundle\Entity\Invoice\InvoiceDetail $invoiceDetail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeInvoiceDetail(\AppBundle\Entity\Invoice\InvoiceDetail $invoiceDetail)
    {
        return $this->invoiceDetail->removeElement($invoiceDetail);
    }

    /**
     * Get invoiceDetail.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceDetail()
    {
        return $this->invoiceDetail;
    }

    /**
     * Set invoiceFormat.
     *
     * @param \AppBundle\Entity\Invoice\InvoiceFormat|null $invoiceFormat
     *
     * @return Invoice
     */
    public function setInvoiceFormat(\AppBundle\Entity\Invoice\InvoiceFormat $invoiceFormat = null)
    {
        $this->invoiceFormat = $invoiceFormat;

        return $this;
    }

    /**
     * Get invoiceFormat.
     *
     * @return \AppBundle\Entity\Invoice\InvoiceFormat|null
     */
    public function getInvoiceFormat()
    {
        return $this->invoiceFormat;
    }

    /**
     * Set invoiceType.
     *
     * @param \AppBundle\Entity\Invoice\InvoiceType|null $invoiceType
     *
     * @return Invoice
     */
    public function setInvoiceType(\AppBundle\Entity\Invoice\InvoiceType $invoiceType = null)
    {
        $this->invoiceType = $invoiceType;

        return $this;
    }

    /**
     * Get invoiceType.
     *
     * @return \AppBundle\Entity\Invoice\InvoiceType|null
     */
    public function getInvoiceType()
    {
        return $this->invoiceType;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Invoice
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
     * Set paymentMethod.
     *
     * @param \AppBundle\Entity\Invoice\PaymentMethod|null $paymentMethod
     *
     * @return Invoice
     */
    public function setPaymentMethod(\AppBundle\Entity\Invoice\PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod.
     *
     * @return \AppBundle\Entity\Invoice\PaymentMethod|null
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }
}

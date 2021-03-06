<?php

namespace AppBundle\Entity\CashRegister;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CashRegisterRepository")
 * @ORM\Table(name="cash_register")
 * @ORM\HasLifecycleCallbacks()
 */
class CashRegister
{
    const SLUG_KP = "rodzaje-kp";
    const SLUG_KW = "rodzaje-kw";
    const SLUG_KP_TOWAR = "towar-kp";
    const SLUG_KW_B = "rodzje-kw-bezgotowka";

    const TYPE_KP = 'kp';
    const TYPE_KW = 'kw';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\CashRegister\CashRegisterReport",
     *     inversedBy="cashRegister"
     * )
     * @ORM\JoinColumn(
     *     name="report_id",
     *     referencedColumnName="id",
     *     nullable=true
     * )
     */
    private $report;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\CashRegister\CashRegisterReport"
     * )
     * @ORM\JoinColumn(
     *     name="monthly_report_id",
     *     referencedColumnName="id",
     *     nullable=true
     * )
     */
    private $monthlyReport;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\CashRegister\CashRegisterDetail",
     *     mappedBy="cashRegister",
     *     cascade={"persist"}
     * )
     */
    private $cashRegisterDetails;

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
    private $driver;

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
    protected $client;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private $user;

    /************************************************/
    /**
     * @ORM\Column(type="string", name="transaction_type", length=10)
     */
    private $transactionType; //KW | KP

    /**
     * @ORM\Column(type="string", unique=true, name="cash_register_number")
     */
    private $cashRegisterNumber;

    /**
     * @ORM\Column(type="datetime", name="transaction_date")
     */
    private $transactionDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSettlement = false;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

    /**
     * @ORM\Column(type="datetime", name="create_date")
     */
    private $createDate;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->updateDate = new \DateTime();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cashRegisterDetails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createDate = new \DateTime();
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
     * @return CashRegister
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
     * Set cashRegisterNumber.
     *
     * @param string $cashRegisterNumber
     *
     * @return CashRegister
     */
    public function setCashRegisterNumber($cashRegisterNumber)
    {
        $this->cashRegisterNumber = $cashRegisterNumber;

        return $this;
    }

    /**
     * Get cashRegisterNumber.
     *
     * @return string
     */
    public function getCashRegisterNumber()
    {
        return $this->cashRegisterNumber;
    }

    /**
     * Set transactionDate.
     *
     * @param \DateTime $transactionDate
     *
     * @return CashRegister
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    /**
     * Get transactionDate.
     *
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * Set title.
     *
     * @param string|null $title
     *
     * @return CashRegister
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
     * Set isSettlement.
     *
     * @param bool $isSettlement
     *
     * @return CashRegister
     */
    public function setIsSettlement($isSettlement)
    {
        $this->isSettlement = $isSettlement;

        return $this;
    }

    /**
     * Get isSettlement.
     *
     * @return bool
     */
    public function getIsSettlement()
    {
        return $this->isSettlement;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return CashRegister
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
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return CashRegister
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
     * Set report.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegisterReport|null $report
     *
     * @return CashRegister
     */
    public function setReport(\AppBundle\Entity\CashRegister\CashRegisterReport $report = null)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report.
     *
     * @return \AppBundle\Entity\CashRegister\CashRegisterReport|null
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Set monthlyReport.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegisterReport|null $monthlyReport
     *
     * @return CashRegister
     */
    public function setMonthlyReport(\AppBundle\Entity\CashRegister\CashRegisterReport $monthlyReport = null)
    {
        $this->monthlyReport = $monthlyReport;

        return $this;
    }

    /**
     * Get monthlyReport.
     *
     * @return \AppBundle\Entity\CashRegister\CashRegisterReport|null
     */
    public function getMonthlyReport()
    {
        return $this->monthlyReport;
    }

    /**
     * Add cashRegisterDetail.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegisterDetail $cashRegisterDetail
     *
     * @return CashRegister
     */
    public function addCashRegisterDetail(\AppBundle\Entity\CashRegister\CashRegisterDetail $cashRegisterDetail)
    {
        $cashRegisterDetail->setCashRegister($this);
        $this->cashRegisterDetails[] = $cashRegisterDetail;

        return $this;
    }

    /**
     * Remove cashRegisterDetail.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegisterDetail $cashRegisterDetail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCashRegisterDetail(\AppBundle\Entity\CashRegister\CashRegisterDetail $cashRegisterDetail)
    {
        $cashRegisterDetail->setCashRegister(null);

        return $this->cashRegisterDetails->removeElement($cashRegisterDetail);
    }

    /**
     * Get cashRegisterDetails.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCashRegisterDetails()
    {
        return $this->cashRegisterDetails;
    }

    /**
     * Set driver.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Driver|null $driver
     *
     * @return CashRegister
     */
    public function setDriver(\AppBundle\Entity\ApiTaxi360\Driver $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver.
     *
     * @return \AppBundle\Entity\ApiTaxi360\Driver|null
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set client.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Client|null $client
     *
     * @return CashRegister
     */
    public function setClient(\AppBundle\Entity\ApiTaxi360\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client.
     *
     * @return \AppBundle\Entity\ApiTaxi360\Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return CashRegister
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
}

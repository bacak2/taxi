<?php

namespace AppBundle\Entity\CashRegister;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CashRegisterReportRepository")
 * @ORM\Table(name="cash_register_report")
 * @ORM\HasLifecycleCallbacks()
 */
class CashRegisterReport
{
    const DAILY_REPORT = 'dr';
    const MONTHLY_REPORT = 'mr';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\CashRegister\CashRegister",
     *     mappedBy="report"
     * )
     */
    private $cashRegister;

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

    /**
     * @ORM\Column(type="string", length=10,name="report_type")
     */
    private $reportType;

    /**
     * @ORM\Column(type="string", name="report_number", unique=true)
     */
    private $reportNumber;

    /**
     * @ORM\Column(type="date", name="report_for_date")
     */
    private $reportForDate;

    /**
     * @ORM\Column(type="integer", name="kp_count")
     */
    private $kpCount;

    /**
     * @ORM\Column(type="decimal", scale=2, name="kp_amount")
     */
    private $kpAmount;

    /**
     * @ORM\Column(type="integer", name="kw_count")
     */
    private $kwCount;

    /**
     * @ORM\Column(type="decimal", scale=2, name="kw_amount")
     */
    private $kwAmount;

    /**
     * @ORM\Column(type="decimal", scale=2, name="amount")
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", scale=2, name="prev_amount")
     */
    private $prevAmount;

    /**
     * @ORM\Column(type="decimal", scale=2, name="change_amount")
     */
    private $changeAmount;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

    /**
     * @ORM\Column(type="datetime", name="create_date")
     */
    private $createDate;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cashRegister = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createDate = new \DateTime();
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
     * Set reportType.
     *
     * @param string $reportType
     *
     * @return CashRegisterReport
     */
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get reportType.
     *
     * @return string
     */
    public function getReportType()
    {
        return $this->reportType;
    }

    /**
     * Set reportNumber.
     *
     * @param string $reportNumber
     *
     * @return CashRegisterReport
     */
    public function setReportNumber($reportNumber)
    {
        $this->reportNumber = $reportNumber;

        return $this;
    }

    /**
     * Get reportNumber.
     *
     * @return string
     */
    public function getReportNumber()
    {
        return $this->reportNumber;
    }

    /**
     * Set reportForDate.
     *
     * @param \DateTime $reportForDate
     *
     * @return CashRegisterReport
     */
    public function setReportForDate($reportForDate)
    {
        $this->reportForDate = $reportForDate;

        return $this;
    }

    /**
     * Get reportForDate.
     *
     * @return \DateTime
     */
    public function getReportForDate()
    {
        return $this->reportForDate;
    }

    /**
     * Set kpCount.
     *
     * @param int $kpCount
     *
     * @return CashRegisterReport
     */
    public function setKpCount($kpCount)
    {
        $this->kpCount = $kpCount;

        return $this;
    }

    /**
     * Get kpCount.
     *
     * @return int
     */
    public function getKpCount()
    {
        return $this->kpCount;
    }

    /**
     * Set kpAmount.
     *
     * @param string $kpAmount
     *
     * @return CashRegisterReport
     */
    public function setKpAmount($kpAmount)
    {
        $this->kpAmount = $kpAmount;

        return $this;
    }

    /**
     * Get kpAmount.
     *
     * @return string
     */
    public function getKpAmount()
    {
        return $this->kpAmount;
    }

    /**
     * Set kwCount.
     *
     * @param int $kwCount
     *
     * @return CashRegisterReport
     */
    public function setKwCount($kwCount)
    {
        $this->kwCount = $kwCount;

        return $this;
    }

    /**
     * Get kwCount.
     *
     * @return int
     */
    public function getKwCount()
    {
        return $this->kwCount;
    }

    /**
     * Set kwAmount.
     *
     * @param string $kwAmount
     *
     * @return CashRegisterReport
     */
    public function setKwAmount($kwAmount)
    {
        $this->kwAmount = $kwAmount;

        return $this;
    }

    /**
     * Get kwAmount.
     *
     * @return string
     */
    public function getKwAmount()
    {
        return $this->kwAmount;
    }

    /**
     * Set amount.
     *
     * @param string $amount
     *
     * @return CashRegisterReport
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set prevAmount.
     *
     * @param string $prevAmount
     *
     * @return CashRegisterReport
     */
    public function setPrevAmount($prevAmount)
    {
        $this->prevAmount = $prevAmount;

        return $this;
    }

    /**
     * Get prevAmount.
     *
     * @return string
     */
    public function getPrevAmount()
    {
        return $this->prevAmount;
    }

    /**
     * Set changeAmount.
     *
     * @param string $changeAmount
     *
     * @return CashRegisterReport
     */
    public function setChangeAmount($changeAmount)
    {
        $this->changeAmount = $changeAmount;

        return $this;
    }

    /**
     * Get changeAmount.
     *
     * @return string
     */
    public function getChangeAmount()
    {
        return $this->changeAmount;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return CashRegisterReport
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
     * @return CashRegisterReport
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
     * Add cashRegister.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegister $cashRegister
     *
     * @return CashRegisterReport
     */
    public function addCashRegister(\AppBundle\Entity\CashRegister\CashRegister $cashRegister)
    {
        $cashRegister->setCashRegisterNumber($this);
        $this->cashRegister[] = $cashRegister;

        return $this;
    }

    /**
     * Remove cashRegister.
     *
     * @param \AppBundle\Entity\CashRegister\CashRegister $cashRegister
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCashRegister(\AppBundle\Entity\CashRegister\CashRegister $cashRegister)
    {
        $cashRegister->setCashRegisterNumber(null);
        return $this->cashRegister->removeElement($cashRegister);
    }

    /**
     * Get cashRegister.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCashRegister()
    {
        return $this->cashRegister;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return CashRegisterReport
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

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\EnumeratorRepository"
 * )
 * @ORM\Table(
 *     name="enumerator",
 *     uniqueConstraints={
 *          @UniqueConstraint(name="enum_idx", columns={"number","enum_type","month","year"})}
 * )
 */
class Enumerator
{
    const TYPE_KW = 'KW';
    const TYPE_KP = 'KP';
    const TYPE_FV = 'FV';
    const TYPE_RAPORT_KASOWY = 'RK';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, name="enum_type")
     */
    private $enumType;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $month;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    /**
     * @ORM\Column(type="boolean", name="is_deleted")
     */
    private $isDeleted = false;

    /**
     * @ORM\Column(type="datetime", name="create_date")
     */
    private $createDate;

    /**
     * @var User $user
     */
    private $user;

    /**
     * Enumerator constructor.
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
        $this->month = $this->createDate->format('m');
        $this->year = $this->createDate->format('Y');
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
     * Set enumType.
     *
     * @param string $enumType
     *
     * @return Enumerator
     */
    public function setEnumType($enumType)
    {
        $this->enumType = $enumType;

        return $this;
    }

    /**
     * Get enumType.
     *
     * @return string
     */
    public function getEnumType()
    {
        return $this->enumType;
    }

    /**
     * Set number.
     *
     * @param int $number
     *
     * @return Enumerator
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set month.
     *
     * @param int $month
     *
     * @return Enumerator
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month.
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year.
     *
     * @param int $year
     *
     * @return Enumerator
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Enumerator
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return Enumerator
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
     * @return mixed
     */
    public function getisDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     * @return Enumerator
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Enumerator
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return sprintf("%s/%s/%s/%s",
                $this->enumType, $this->getNumber(), $this->getMonth(),$this->getYear());
    }

    /**
     * @return string
     */
    public function getCashRegisterNumber()
    {
        return sprintf("%s/%s/%s/%s/%s",
            $this->enumType, $this->getNumber(), $this->getMonth(),
            $this->getYear(), $this->user->getPosition());
    }

    /**
     * @return string
     */
    public function getCashRegisterReportNumber()
    {
        return sprintf("%s/%s/%s/%s/%s",
            $this->enumType, $this->getNumber(), $this->getMonth(),
            $this->getYear(), $this->user->getPosition());
    }
}

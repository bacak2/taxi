<?php

namespace AppBundle\Entity\ApiTaxi360;


use AppBundle\Service\HelperService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PassengerRepository")
 * @ORM\Table(name="passenger")
 * @ORM\HasLifecycleCallbacks()
 */
class Passenger
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

    /**
     * @ORM\Column(type="integer", name="taxi_passenger_id", nullable=true)
     */
    private $taxiPassengerId;

    /**
     * @ORM\Column(type="string")
     */
    private $action;

    /**
     * @ORM\Column(type="datetime", name="movement_date")
     */
    private $movementDate;

    /**
     * @ORM\Column(type="integer", name="taxi_client_id", nullable=true)
     */
    private $taxiClientId;

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
    private $department;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", nullable=true)
     * ACTIVE | RETIRED
     */
    private $status;

    /**
     * @ORM\Column(type="string", name="mobile_number", nullable=true)
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", name="operator_login_name", nullable=true)
     */
    private $operatorLoginName;

    /**
     * @ORM\Column(type="string", name="operator_status", nullable=true)
     * ACTIVE | RETIRED
     */
    private $operatorStatus;

    /**
     * @ORM\Column(type="integer", name="sequence_id")
     */
    private $sequenceId;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $hash;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

    /**
     * Passenger constructor.
     */
    public function __construct()
    {
        $this->hash = HelperService::generateHash($this->id);
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
     * Set taxiPassengerId.
     *
     * @param int|null $taxiPassengerId
     *
     * @return Passenger
     */
    public function setTaxiPassengerId($taxiPassengerId = null)
    {
        $this->taxiPassengerId = $taxiPassengerId;

        return $this;
    }

    /**
     * Get taxiPassengerId.
     *
     * @return int|null
     */
    public function getTaxiPassengerId()
    {
        return $this->taxiPassengerId;
    }

    /**
     * Set action.
     *
     * @param string $action
     *
     * @return Passenger
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action.
     *
     * @return string
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
     * @return Passenger
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
     * Set taxiClientId.
     *
     * @param int|null $taxiClientId
     *
     * @return Passenger
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
     * Set firstName.
     *
     * @param string|null $firstName
     *
     * @return Passenger
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
     * @return Passenger
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
     * Set department.
     *
     * @param string|null $department
     *
     * @return Passenger
     */
    public function setDepartment($department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department.
     *
     * @return string|null
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set position.
     *
     * @param string|null $position
     *
     * @return Passenger
     */
    public function setPosition($position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return string|null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set number.
     *
     * @param string|null $number
     *
     * @return Passenger
     */
    public function setNumber($number = null)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Passenger
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
     * Set mobileNumber.
     *
     * @param string|null $mobileNumber
     *
     * @return Passenger
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
     * Set operatorLoginName.
     *
     * @param string|null $operatorLoginName
     *
     * @return Passenger
     */
    public function setOperatorLoginName($operatorLoginName = null)
    {
        $this->operatorLoginName = $operatorLoginName;

        return $this;
    }

    /**
     * Get operatorLoginName.
     *
     * @return string|null
     */
    public function getOperatorLoginName()
    {
        return $this->operatorLoginName;
    }

    /**
     * Set operatorStatus.
     *
     * @param string|null $operatorStatus
     *
     * @return Passenger
     */
    public function setOperatorStatus($operatorStatus = null)
    {
        $this->operatorStatus = $operatorStatus;

        return $this;
    }

    /**
     * Get operatorStatus.
     *
     * @return string|null
     */
    public function getOperatorStatus()
    {
        return $this->operatorStatus;
    }

    /**
     * Set sequenceId.
     *
     * @param int $sequenceId
     *
     * @return Passenger
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
     * Set hash.
     *
     * @param string $hash
     *
     * @return Passenger
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Passenger
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
     * @return Passenger
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

    public function __toString()
    {
        return sprintf('%s %s', $this->firstName, $this->surname);
    }
}

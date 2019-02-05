<?php

namespace AppBundle\Entity\ApiTaxi360;

use AppBundle\Service\HelperService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TerminalRepository")
 * @ORM\Table(name="terminal")
 * @ORM\HasLifecycleCallbacks()
 */
class Terminal
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $tid;

    /**
     * @ORM\Column(type="integer", name="taxi_terminal_id", nullable=true)
     */
    private $taxiTerminalId;

    /**
     * @ORM\Column(type="string")
     */
    private $action;

    /**
     * @ORM\Column(type="datetime", name="movement_date")
     */
    private $movementDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $model;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", name="taxi_driver_id", nullable=true)
     */
    private $taxiDriverId;

    /**
     * @ORM\Column(type="integer", name="interface_id", nullable=true)
     */
    private $interfaceId;

    /**
     * @ORM\Column(type="string", name="interface_model", nullable=true)
     */
    private $interfaceModel;

    /**
     * @ORM\Column(type="string", name="interface_serial_number", nullable=true)
     */
    private $interfaceSerialNumber;

    /**
     * @ORM\Column(type="integer", name="sim_card_id", nullable=true)
     */
    private $simCardId;

    /**
     * @ORM\Column(type="string", name="sim_card_service_provider", nullable=true)
     */
    private $simCardServiceProvider;

    /**
     * @ORM\Column(type="string",name="sim_card_serial_number", nullable=true)
     */
    private $simCardSerialNumber;

    /**
     * @ORM\Column(type="string", name="sim_card_phone_number", nullable=true)
     */
    private $simCardPhoneNumber;

    /**
     * @ORM\Column(type="string", name="sim_card_ipaddress", nullable=true)
     */
    private $simCardIPAddress;

    /**
     * @ORM\Column(type="integer", name="sequence_id", nullable=true)
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
     * Terminal constructor.
     */
    public function __construct()
    {
        $this->hash = HelperService::generateHash($this->id);
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tid.
     *
     * @param string|null $tid
     *
     * @return Terminal
     */
    public function setTid($tid = null)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid.
     *
     * @return string|null
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Set taxiTerminalId.
     *
     * @param int|null $taxiTerminalId
     *
     * @return Terminal
     */
    public function setTaxiTerminalId($taxiTerminalId = null)
    {
        $this->taxiTerminalId = $taxiTerminalId;

        return $this;
    }

    /**
     * Get taxiTerminalId.
     *
     * @return int|null
     */
    public function getTaxiTerminalId()
    {
        return $this->taxiTerminalId;
    }

    /**
     * Set action.
     *
     * @param string $action
     *
     * @return Terminal
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
     * @return Terminal
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
     * Set model.
     *
     * @param string|null $model
     *
     * @return Terminal
     */
    public function setModel($model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Terminal
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
     * Set taxiDriverId.
     *
     * @param int|null $taxiDriverId
     *
     * @return Terminal
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
     * Set interfaceId.
     *
     * @param int|null $interfaceId
     *
     * @return Terminal
     */
    public function setInterfaceId($interfaceId = null)
    {
        $this->interfaceId = $interfaceId;

        return $this;
    }

    /**
     * Get interfaceId.
     *
     * @return int|null
     */
    public function getInterfaceId()
    {
        return $this->interfaceId;
    }

    /**
     * Set interfaceModel.
     *
     * @param string|null $interfaceModel
     *
     * @return Terminal
     */
    public function setInterfaceModel($interfaceModel = null)
    {
        $this->interfaceModel = $interfaceModel;

        return $this;
    }

    /**
     * Get interfaceModel.
     *
     * @return string|null
     */
    public function getInterfaceModel()
    {
        return $this->interfaceModel;
    }

    /**
     * Set interfaceSerialNumber.
     *
     * @param string|null $interfaceSerialNumber
     *
     * @return Terminal
     */
    public function setInterfaceSerialNumber($interfaceSerialNumber = null)
    {
        $this->interfaceSerialNumber = $interfaceSerialNumber;

        return $this;
    }

    /**
     * Get interfaceSerialNumber.
     *
     * @return string|null
     */
    public function getInterfaceSerialNumber()
    {
        return $this->interfaceSerialNumber;
    }

    /**
     * Set simCardId.
     *
     * @param int|null $simCardId
     *
     * @return Terminal
     */
    public function setSimCardId($simCardId = null)
    {
        $this->simCardId = $simCardId;

        return $this;
    }

    /**
     * Get simCardId.
     *
     * @return int|null
     */
    public function getSimCardId()
    {
        return $this->simCardId;
    }

    /**
     * Set simCardServiceProvider.
     *
     * @param string|null $simCardServiceProvider
     *
     * @return Terminal
     */
    public function setSimCardServiceProvider($simCardServiceProvider = null)
    {
        $this->simCardServiceProvider = $simCardServiceProvider;

        return $this;
    }

    /**
     * Get simCardServiceProvider.
     *
     * @return string|null
     */
    public function getSimCardServiceProvider()
    {
        return $this->simCardServiceProvider;
    }

    /**
     * Set simCardSerialNumber.
     *
     * @param string|null $simCardSerialNumber
     *
     * @return Terminal
     */
    public function setSimCardSerialNumber($simCardSerialNumber = null)
    {
        $this->simCardSerialNumber = $simCardSerialNumber;

        return $this;
    }

    /**
     * Get simCardSerialNumber.
     *
     * @return string|null
     */
    public function getSimCardSerialNumber()
    {
        return $this->simCardSerialNumber;
    }

    /**
     * Set simCardPhoneNumber.
     *
     * @param string|null $simCardPhoneNumber
     *
     * @return Terminal
     */
    public function setSimCardPhoneNumber($simCardPhoneNumber = null)
    {
        $this->simCardPhoneNumber = $simCardPhoneNumber;

        return $this;
    }

    /**
     * Get simCardPhoneNumber.
     *
     * @return string|null
     */
    public function getSimCardPhoneNumber()
    {
        return $this->simCardPhoneNumber;
    }

    /**
     * Set simCardIPAddress.
     *
     * @param string|null $simCardIPAddress
     *
     * @return Terminal
     */
    public function setSimCardIPAddress($simCardIPAddress = null)
    {
        $this->simCardIPAddress = $simCardIPAddress;

        return $this;
    }

    /**
     * Get simCardIPAddress.
     *
     * @return string|null
     */
    public function getSimCardIPAddress()
    {
        return $this->simCardIPAddress;
    }

    /**
     * Set sequenceId.
     *
     * @param int $sequenceId
     *
     * @return Terminal
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
     * @return Terminal
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
     * @return Terminal
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
     * @return Terminal
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

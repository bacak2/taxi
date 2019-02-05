<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlockadeRepository")
 * @ORM\Table(name="blockade")
 * @ORM\HasLifecycleCallbacks()
 */
class Blockade
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\ApiTaxi360\Driver",
     *     inversedBy="blockade"
     * )
     * @ORM\JoinColumn(
     *     name="driver_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )
     */
    private $driver;

    /**
     * @ORM\Column(type="date", name="blockade_date")
     */
    private $blockadeDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", name="blockade_type")
     */
    private $blockadeType;

    /**
     * @ORM\Column(type="boolean", name="is_active")
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="datetime", name="delete_date", nullable=true)
     */
    private $deleteDate;

    /**
     * @ORM\Column(type="datetime", name="update_date")
     */
    private $updateDate;

    /**
     * @ORM\Column(type="datetime", name="create_date")
     */
    private $createDate;

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function updateDate()
    {
        $this->updateDate = new \DateTime();
    }

    /**
     * Blockade constructor.
     */
    public function __construct()
    {
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
     * Set blockadeDate.
     *
     * @param \DateTime $blockadeDate
     *
     * @return Blockade
     */
    public function setBlockadeDate($blockadeDate)
    {
        $this->blockadeDate = $blockadeDate;

        return $this;
    }

    /**
     * Get blockadeDate.
     *
     * @return \DateTime
     */
    public function getBlockadeDate()
    {
        return $this->blockadeDate;
    }

    /**
     * Set comment.
     *
     * @param string|null $comment
     *
     * @return Blockade
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return Blockade
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set deleteDate.
     *
     * @param \DateTime|null $deleteDate
     *
     * @return Blockade
     */
    public function setDeleteDate($deleteDate = null)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    /**
     * Get deleteDate.
     *
     * @return \DateTime|null
     */
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return Blockade
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
     * @return Blockade
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
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Blockade
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
     * Set driver.
     *
     * @param \AppBundle\Entity\ApiTaxi360\Driver|null $driver
     *
     * @return Blockade
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
     * @return mixed
     */
    public function getBlockadeType()
    {
        return $this->blockadeType;
    }

    /**
     * @param mixed $blockadeType
     * @return Blockade
     */
    public function setBlockadeType($blockadeType)
    {
        $this->blockadeType = $blockadeType;
        return $this;
    }


}

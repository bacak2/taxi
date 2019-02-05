<?php

namespace AppBundle\Entity\Dictionary;

/**
 * ChargeType
 */
class ChargeType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $chargeType;

    /**
     * @var string
     */
    private $chargeSlug;


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
     * Set chargeType.
     *
     * @param string $chargeType
     *
     * @return ChargeType
     */
    public function setChargeType($chargeType)
    {
        $this->chargeType = $chargeType;

        return $this;
    }

    /**
     * Get chargeType.
     *
     * @return string
     */
    public function getChargeType()
    {
        return $this->chargeType;
    }

    /**
     * Set chargeSlug.
     *
     * @param string $chargeSlug
     *
     * @return ChargeType
     */
    public function setChargeSlug($chargeSlug)
    {
        $this->chargeSlug = $chargeSlug;

        return $this;
    }

    /**
     * Get chargeSlug.
     *
     * @return string
     */
    public function getChargeSlug()
    {
        return $this->chargeSlug;
    }
    /**
     * @ORM\PrePersist
     */
    public function pre()
    {
        // Add your code here
    }
}

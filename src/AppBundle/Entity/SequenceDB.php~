<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sequence_db")
 */
class SequenceDB
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $sequenceId;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * SequenceDB constructor.
     */
    public function __construct()
    {
        $this->createDate = new \DateTime();
    }

    /**
     * Set sequenceId.
     *
     * @param int $sequenceId
     *
     * @return SequenceDB
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
     * Set content.
     *
     * @param string $content
     *
     * @return SequenceDB
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return SequenceDB
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
}

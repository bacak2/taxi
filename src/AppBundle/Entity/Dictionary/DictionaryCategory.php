<?php

namespace AppBundle\Entity\Dictionary;

use AppBundle\Service\HelperService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DictionaryCategoryRepository")
 * @ORM\Table(name="dictionary_category")
 * @ORM\HasLifecycleCallbacks()
 */
class DictionaryCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="category_name")
     */
    private $name;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Dictionary\DictionaryParam",
     *     mappedBy="category",
     *     cascade={"persist"}
     * )
     */
    private $params;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->params = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return DictionaryCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get params.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function pre()
    {
        $this->slug = HelperService::sluggify($this->name);
    }

    public function __toString()
    {
        return $this->name;
    }
}

<?php

namespace AppBundle\Entity\Params;


use AppBundle\Service\HelperService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParamCategoryRepository")
 * @ORM\Table(name="param_category")
 * @ORM\HasLifecycleCallbacks()
 */
class ParamCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Params\Param",
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
     * @return ParamCategory
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return ParamCategory
     */
    public function setSlug()
    {
        $this->slug = HelperService::sluggify($this->name);

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add param.
     *
     * @param \AppBundle\Entity\Params\Param $param
     *
     * @return ParamCategory
     */
    public function addParam(\AppBundle\Entity\Params\Param $param)
    {
        $param->setCategory($this);
        $this->params[] = $param;

        return $this;
    }

    /**
     * Remove param.
     *
     * @param \AppBundle\Entity\Params\Param $param
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeParam(\AppBundle\Entity\Params\Param $param)
    {
        $param->setCategory(null);
        return $this->params->removeElement($param);
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

<?php

namespace AppBundle\Entity\Dictionary;

use AppBundle\Service\HelperService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DictionaryRepository")
 * @ORM\Table(name="dictionary",)
 * @ORM\HasLifecycleCallbacks()
 */
class DictionaryParam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="value")
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", name="editable")
     */
    private $editable = false;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Dictionary\DictionaryCategory",
     *     inversedBy="params"
     * )
     * @ORM\JoinColumn(
     *     name="category_id",
     *     referencedColumnName="id"
     * )
     */
    private $category;

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
     * @return DictionaryParam
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
     * Set editable.
     *
     * @param bool $editable
     *
     * @return DictionaryParam
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editable.
     *
     * @return bool
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set category.
     *
     * @param \AppBundle\Entity\Params\ParamCategory|null $category
     *
     * @return DictionaryParam
     */
    public function setCategory(\AppBundle\Entity\Params\ParamCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\Params\ParamCategory|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return DictionaryParam
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
     * Set slug.
     *
     * @param string|null $slug
     *
     * @return DictionaryParam
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
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

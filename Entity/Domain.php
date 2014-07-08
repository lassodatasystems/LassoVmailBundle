<?php

namespace Lasso\VmailBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 */
class Domain
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var DateTime
     */
    private $created;

    /**
     * @var DateTime
     */
    private $modified;

    /**
     * @var boolean
     */
    private $active = 1;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var LocalDomain
     */
    private $local;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Domain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Domain
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param DateTime $created
     *
     * @return Domain
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param DateTime $modified
     *
     * @return Domain
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Domain
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set local
     *
     * @param LocalDomain $local
     */
    public function setLocalDomain(LocalDomain $local = null)
    {
        if (!empty($local)) {
            $local->setDomain($this);
        }
        $this->local = $local;
    }

    /**
     * Get LocalDomain
     *
     * @return LocalDomain
     */
    public function getLocalDomain()
    {
        return $this->local;
    }

    /**
     * @return bool
     *
     * check to see if this domain has a local domain entry
     */
    public function isLocalDomain()
    {
        return !is_null($this->getLocalDomain());
    }
}

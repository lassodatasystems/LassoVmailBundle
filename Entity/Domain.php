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
     * @var integer
     */
    private $aliases = 0;

    /**
     * @var integer
     */
    private $mailboxes = 0;

    /**
     * @var integer
     */
    private $maxQuota = 0;

    /**
     * @var integer
     */
    private $quota = 0;

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
     * Set aliases
     *
     * @param integer $aliases
     *
     * @return Domain
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;

        return $this;
    }

    /**
     * Get aliases
     *
     * @return integer
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Set mailboxes
     *
     * @param integer $mailboxes
     *
     * @return Domain
     */
    public function setMailboxes($mailboxes)
    {
        $this->mailboxes = $mailboxes;

        return $this;
    }

    /**
     * Get mailboxes
     *
     * @return integer
     */
    public function getMailboxes()
    {
        return $this->mailboxes;
    }

    /**
     * Set maxQuota
     *
     * @param integer $maxQuota
     *
     * @return Domain
     */
    public function setMaxQuota($maxQuota)
    {
        $this->maxQuota = $maxQuota;

        return $this;
    }

    /**
     * Get maxQuota
     *
     * @return integer
     */
    public function getMaxQuota()
    {
        return $this->maxQuota;
    }

    /**
     * Set quota
     *
     * @param integer $quota
     *
     * @return Domain
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Get quota
     *
     * @return integer
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Set transport
     *
     * @param string $transport
     *
     * @return Domain
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
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
}

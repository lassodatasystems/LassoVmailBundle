<?php

namespace Lasso\VmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 */
class Domain
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $aliases;

    /**
     * @var integer
     */
    private $mailboxes;

    /**
     * @var integer
     */
    private $maxquota;

    /**
     * @var integer
     */
    private $quota;

    /**
     * @var string
     */
    private $transport;

    /**
     * @var boolean
     */
    private $backupmx;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set name
     *
     * @param string $name
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
     * Set maxquota
     *
     * @param integer $maxquota
     * @return Domain
     */
    public function setMaxquota($maxquota)
    {
        $this->maxquota = $maxquota;
    
        return $this;
    }

    /**
     * Get maxquota
     *
     * @return integer 
     */
    public function getMaxquota()
    {
        return $this->maxquota;
    }

    /**
     * Set quota
     *
     * @param integer $quota
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
     * Set backupmx
     *
     * @param boolean $backupmx
     * @return Domain
     */
    public function setBackupmx($backupmx)
    {
        $this->backupmx = $backupmx;
    
        return $this;
    }

    /**
     * Get backupmx
     *
     * @return boolean 
     */
    public function getBackupmx()
    {
        return $this->backupmx;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
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
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
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
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set active
     *
     * @param boolean $active
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

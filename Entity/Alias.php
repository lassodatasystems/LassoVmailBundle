<?php

namespace Lasso\VmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alias
 */
class Alias
{
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
     * @var \Lasso\VmailBundle\Entity\Email
     */
    private $destination;

    /**
     * @var \Lasso\VmailBundle\Entity\Email
     */
    private $source;


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Alias
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
     * @return Alias
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
     * @return Alias
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
     * Set destination
     *
     * @param \Lasso\VmailBundle\Entity\Email $destination
     * @return Alias
     */
    public function setDestination(\Lasso\VmailBundle\Entity\Email $destination = null)
    {
        $this->destination = $destination;
    
        return $this;
    }

    /**
     * Get destination
     *
     * @return \Lasso\VmailBundle\Entity\Email 
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set source
     *
     * @param \Lasso\VmailBundle\Entity\Email $source
     * @return Alias
     */
    public function setSource(\Lasso\VmailBundle\Entity\Email $source = null)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return \Lasso\VmailBundle\Entity\Email 
     */
    public function getSource()
    {
        return $this->source;
    }
}

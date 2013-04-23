<?php

namespace Lasso\VmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 */
class Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $localPart;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $modified;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lasso\VmailBundle\Entity\Domain
     */
    private $domain;


    /**
     * Set email
     *
     * @param string $email
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set localPart
     *
     * @param string $localPart
     * @return Email
     */
    public function setLocalPart($localPart)
    {
        $this->localPart = $localPart;
    
        return $this;
    }

    /**
     * Get localPart
     *
     * @return string 
     */
    public function getLocalPart()
    {
        return $this->localPart;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Email
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
     * @return Email
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set domain
     *
     * @param \Lasso\VmailBundle\Entity\Domain $domain
     * @return Email
     */
    public function setDomain(\Lasso\VmailBundle\Entity\Domain $domain = null)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return \Lasso\VmailBundle\Entity\Domain 
     */
    public function getDomain()
    {
        return $this->domain;
    }
}

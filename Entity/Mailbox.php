<?php

namespace Lasso\VmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mailbox
 */
class Mailbox
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $maildir;

    /**
     * @var integer
     */
    private $quota;

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
    private $email;


    /**
     * Set username
     *
     * @param string $username
     * @return Mailbox
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Mailbox
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Mailbox
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
     * Set maildir
     *
     * @param string $maildir
     * @return Mailbox
     */
    public function setMaildir($maildir)
    {
        $this->maildir = $maildir;
    
        return $this;
    }

    /**
     * Get maildir
     *
     * @return string 
     */
    public function getMaildir()
    {
        return $this->maildir;
    }

    /**
     * Set quota
     *
     * @param integer $quota
     * @return Mailbox
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
     * Set created
     *
     * @param \DateTime $created
     * @return Mailbox
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
     * @return Mailbox
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
     * @return Mailbox
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
     * Set email
     *
     * @param \Lasso\VmailBundle\Entity\Email $email
     * @return Mailbox
     */
    public function setEmail(\Lasso\VmailBundle\Entity\Email $email = null)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return \Lasso\VmailBundle\Entity\Email 
     */
    public function getEmail()
    {
        return $this->email;
    }
}

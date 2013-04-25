<?php

namespace Lasso\VmailBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Mailbox
 */
class Mailbox
{
    /**
     * @var string
     */
    private $username = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var string
     */
    private $maildir = '';

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
     * @var Email
     */
    private $email;

    /**
     * Set username
     *
     * @param string $username
     *
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
     *
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
     * Set maildir
     *
     * @param string $maildir
     *
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
     *
     * @return Mailbox
     */
    public function setQuota($quota)
    {
        if($quota){
            $this->quota = $quota;
        }

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
     * @param DateTime $created
     *
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
     * @param Email $email
     *
     * @return Mailbox
     */
    public function setEmail(Email $email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return Email
     */
    public function getEmail()
    {
        return $this->email;
    }
}

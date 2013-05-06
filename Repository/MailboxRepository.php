<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Entity\Mailbox;
use Lasso\VmailBundle\Exception\VmailException;

/**
 * Class MailboxRepository
 * @package Lasso\VmailBundle\Repository
 */
class MailboxRepository extends EntityRepository
{

    /**
     * @param string $localPart
     * @param Email  $email
     *
     * @return Mailbox
     */
    public function getMailbox($username)
    {
        $this->validateUserName($username);

        $mailbox = new Mailbox();
        $mailbox->setUsername($username);

        return $mailbox;
    }

    private function validateUserName($username)
    {
        if (strlen($username) <= 3) {
            throw VmailException::invalidUsername($username);
        }
    }
}

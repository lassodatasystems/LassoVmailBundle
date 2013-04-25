<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Entity\Mailbox;

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
    public function getMailbox($localPart, Email $email)
    {
        $mailbox = new Mailbox();
        $mailbox->setUsername($localPart);
        $mailbox->setEmail($email);
        return $mailbox;
    }
}

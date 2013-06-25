<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Mailbox;
use Lasso\VmailBundle\Exception\VmailException;

/**
 * Class MailboxRepository
 * @package Lasso\VmailBundle\Repository
 */
class MailboxRepository extends EntityRepository
{

    /**
     * @param $username
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

    /**
     * @param $username
     *
     * @throws VmailException
     */
    private function validateUserName($username)
    {
        if (strlen($username) <= 3) {
            throw VmailException::invalidUsername($username);
        }
    }

    /**
     * @param string $search
     * @param bool   $limit
     * @param bool   $offset
     *
     * @return Mailbox[]
     */
    public function getList($search = '', $limit = false, $offset = false, $sort = [])
    {
        $qb = $this->createQueryBuilder('m');
        if ($search) {
            $qb->where("m.username LIKE :username");
            $qb->setParameter('username', "%$search%");
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if (!empty($sort->property)) {
            $qb->orderBy('m.' . $sort->property, $sort->direction);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $search
     *
     * @return int count
     */
    public function getCount($search = '')
    {
        $mailboxes = $this->getList($search);

        return count($mailboxes);
    }
}

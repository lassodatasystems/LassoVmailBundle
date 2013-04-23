<?php

namespace Lasso\VmailBundle;

use Doctrine\ORM\EntityManager;
use Lasso\VmailBundle\Exception\VmailException;
use Lasso\VmailBundle\Repository\AliasRepository;
use Lasso\VmailBundle\Repository\DomainRepository;
use Lasso\VmailBundle\Repository\EmailRepository;
use Lasso\VmailBundle\Repository\MailboxRepository;
use Monolog\Logger;

/**
 * Class MailboxManager
 * @package Lasso\VmailBundle
 */
class MailboxManager
{
    /**
     * @var $em EntityManager
     */
    private $em = null;

    /**
     * @var AliasRepository
     */
    private $aliasRepository = null;

    /**
     * @var DomainRepository
     */
    private $domainRepository = null;

    /**
     * @var EmailRepository
     */
    private $emailRepository = null;

    /**
     * @var MailboxRepository
     */
    private $mailboxRepository = null;

    /**
     * @var MailboxManager
     */
    private $mailboxManager = null;

    /**
     * @var Logger
     */
    private $logger = null;

    /**
     * @param                   $em EntityManager
     * @param AliasRepository   $aliasRepository
     * @param DomainRepository  $domainRepository
     * @param EmailRepository   $emailRepository
     * @param MailboxRepository $mailboxRepository
     * @param Logger            $logger
     *
     * @internal param MailboxManager $mailboxFactory
     */
    public function __construct(EntityManager $em,
                                AliasRepository $aliasRepository,
                                DomainRepository $domainRepository,
                                EmailRepository $emailRepository,
                                MailboxRepository $mailboxRepository,
                                Logger $logger)
    {
        $this->em                = $em;
        $this->aliasRepository   = $aliasRepository;
        $this->domainRepository  = $domainRepository;
        $this->emailRepository   = $emailRepository;
        $this->mailboxRepository = $mailboxRepository;
        $this->logger            = $logger;
    }

    /**
     * @param $userName
     * @param $hash
     *
     * @throws VmailException
     * @throws VmailException
     * @return int
     */
    public function updatePassword($userName, $hash)
    {
        /** @var $mailbox Mailbox */
        $mailbox = $this->mailboxRepository->find($userName);

        try {
            if (!$mailbox) {
                throw VmailException::mailboxNotFound($userName);
            }
            if (!$this->isValidHash($hash)) {
                throw VmailException::invalidPasswordHash($hash);
            }

            $mailbox->setPassword($hash);
            $this->em->persist($mailbox);
            $this->em->flush();

            return 0;
        } catch (VmailException $e) {
            $this->logger->err($e->getMessage());

            return 1;
        }
        catch (Exception $e) {
            $this->logger->err($e->getMessage());

            return 2;
        }
    }

    /**
     * @param string $userName
     * @param string $password
     * @param string $domain
     * @param bool   $noHash
     * @param int    $quota
     *
     * @return int
     */
    public function createMailbox($userName, $password, $domain, $quota = 0, $noHash = false)
    {
        $email = $userName . '@' . $domain;
        $this->em->beginTransaction();
        try {
            if($this->emailExists($email)) {
                throw VmailException::emailExists($email);
            };
            // check for alias return new if not exists

            // check for email
/*
            $mailbox = $this->mailboxFactory->getMailBox($userName, $password, $domain, $quota);
            $this->em->persist($mailbox);

            $alias = $this->mailboxFactory->getAlias($mailbox->getUsername(), $mailbox->getDomain());
            $this->em->persist($alias);*/

            $this->em->flush();
            $this->em->commit();

            return 0;
        } catch (VmailException $e) {
            $this->logger->err($e->getMessage());
            $this->em->rollback();
            $this->em->close();

            return 1;
        } catch (Exception $e) {
            $this->logger->err($e->getMessage());
            $this->em->rollback();
            $this->em->close();

            return 2;
        }
    }

    private function emailExists($alias){
        $alias = $this->emailRepository->findBy(array('email' => $alias));
        if(!$alias){
            return false;
        }
        return true;
    }

    /**
     * @param $hash
     *
     * @return bool
     */
    private function isValidHash($hash)
    {
        return (substr($hash, 0, 3) == '$1$' && strlen($hash) == 34);
    }
}

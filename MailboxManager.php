<?php

namespace Lasso\VmailBundle;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Exception;
use Lasso\VmailBundle\Entity\Mailbox;
use Lasso\VmailBundle\Exception\VmailException;
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
     * @var AliasManager
     */
    private $aliasManager = null;

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
     * @var Logger
     */
    private $logger = null;

    /**
     * @var integer
     */
    private $defaultQuota = 0;

    /**
     * @var string
     */
    private $rootMailDir = '';

    /**
     * @param EntityManager     $em
     * @param AliasManager      $aliasManager
     * @param DomainRepository  $domainRepository
     * @param EmailRepository   $emailRepository
     * @param MailboxRepository $mailboxRepository
     * @param Logger            $logger
     * @param                   $defaultQuota
     * @param                   $rootMailDir
     *
     * @internal param MailboxManager $mailboxFactory
     */
    public function __construct(EntityManager $em,
                                AliasManager $aliasManager,
                                DomainRepository $domainRepository,
                                EmailRepository $emailRepository,
                                MailboxRepository $mailboxRepository,
                                Logger $logger,
                                $defaultQuota = 0,
                                $rootMailDir = '')
    {
        $this->em                = $em;
        $this->aliasManager      = $aliasManager;
        $this->domainRepository  = $domainRepository;
        $this->emailRepository   = $emailRepository;
        $this->mailboxRepository = $mailboxRepository;
        $this->logger            = $logger;
        $this->defaultQuota      = $defaultQuota;
        $this->rootMailDir       = $rootMailDir;
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
    public function createMailbox($localPart, $password, $domainString, $quota = 0, $noHash = false)
    {
        $emailString = $localPart . '@' . $domainString;
        $quota       = $quota ? $quota : $this->defaultQuota;
        $this->em->beginTransaction();
        try {
            if ($this->emailRepository->exists($emailString)) {
                throw VmailException::emailExists($emailString);
            } else if (!filter_var($emailString, FILTER_VALIDATE_EMAIL)) {
                throw VmailException::invalidEmail($emailString);
            } else if (!$this->isValidUserName($localPart)) {
                throw VmailException::invalidUsername($localPart);
            }

            $domain = $this->domainRepository->getDomain($domainString);

            $email = $this->emailRepository->getEmail($emailString);
            $email->setDomain($domain);

            $mailbox = $this->mailboxRepository->getMailbox($localPart);
            $mailbox->setEmail($email);
            $mailbox->setPassword($this->getPassword($password, $noHash));
            $mailbox->setMaildir($this->getMailDir($localPart));
            $mailbox->setQuota($quota);

            $this->aliasManager->createAlias($email->getEmail(), $email->getEmail());

            $this->em->persist($mailbox);

            $this->em->flush();
            $this->em->commit();

            return $mailbox;
        } catch (Exception $e) {
            $this->em->rollback();
            $this->em->close();

            throw $e;
        }
    }

    /**
     * @param $username
     * @param $quota
     *
     * @throws VmailException
     */
    public function updateQuota($username, $quota)
    {
        /** @var $mailbox Mailbox */
        $mailbox = $this->mailboxRepository->findOneBy(['username' => $username]);
        if ($mailbox) {
            $mailbox->setQuota($quota);
            $this->em->persist($mailbox);
            $this->em->flush();

        } else {
            throw VmailException::userNotFound($username);
        }
    }

    /**
     * @param $username
     *
     * @return string
     * @throws VmailException
     */
    private function getMailDir($username)
    {
        return $this->rootMailDir . '/' . $username[0] . '/' . $username[1] . '/' . $username[2];
    }

    /**
     * @param string $password
     * @param bool   $noHash
     *
     * @return string
     */
    private function getPassword($password, $noHash)
    {
        if ($noHash) {
            if (!$this->isValidHash($password)) {
                throw VmailException::invalidPasswordHash($password);
            }

            return $password;
        } else {
            return $this->hashPassword($password);
        }
    }

    /**
     * @param string $password
     *
     * @return string
     */
    private function hashPassword($password)
    {
        return crypt($password, uniqid('$1$'));
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

    private function isValidUserName($username)
    {
        return strlen($username) >= 3;
    }
}

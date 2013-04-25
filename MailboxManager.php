<?php

namespace Lasso\VmailBundle;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Exception;
use Lasso\VmailBundle\Entity\Mailbox;
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
     * @var integer
     */
    private $defaultQuota = 0;

    /**
     * @var string
     */
    private $rootMailDir = '';

    /**
     * @param EntityManager     $em
     * @param AliasRepository   $aliasRepository
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
                                AliasRepository $aliasRepository,
                                DomainRepository $domainRepository,
                                EmailRepository $emailRepository,
                                MailboxRepository $mailboxRepository,
                                Logger $logger,
                                $defaultQuota,
                                $rootMailDir)
    {
        $this->em                = $em;
        $this->aliasRepository   = $aliasRepository;
        $this->domainRepository  = $domainRepository;
        $this->emailRepository   = $emailRepository;
        $this->mailboxRepository = $mailboxRepository;
        $this->logger            = $logger;
        $this->defaultQuota      = $defaultQuota;
        $this->rootMailDir       = $rootMailDir;
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
    public function createMailbox($localPart, $password, $domainString, $quota = 0, $noHash = false)
    {
        $emailString = $localPart . '@' . $domainString;
        $quota = $quota ? $quota : $this->defaultQuota;
        $this->em->beginTransaction();
        try {
            if ($this->emailExists($emailString)) {
                throw VmailException::emailExists($emailString);
            }

            $domain  = $this->domainRepository->getDomain($domainString);
            $email   = $this->emailRepository->getEmail($localPart, $domain);
            $mailbox = $this->mailboxRepository->getMailbox($localPart, $email);
            $mailbox->setPassword($this->setPassword($password, $noHash));
            $mailbox->setMaildir($this->rootMailDir);
            $mailbox->setQuota($quota);

            $alias = $this->aliasRepository->getAlias($email, $email);

            $this->em->persist($mailbox);
            $this->em->persist($alias);

            $this->em->flush();
            $this->em->commit();

            return 0;
        } catch (VmailException $e) {
            $this->logger->err($e->getMessage());
            $this->em->rollback();
            $this->em->close();

            return $e->getCode();
        } catch (Exception $e) {
            $this->logger->err($e->getMessage());
            echo $e->getMessage();
            $this->em->rollback();
            $this->em->close();

            return 2;
        }
    }

    private function setPassword($password, $noHash){
        if($noHash){
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
    private function hashPassword($password) {
        return crypt($password, uniqid('$1$'));
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function emailExists($email)
    {
        $email = $this->emailRepository->findOneBy(array('email' => $email));
        if (!$email) {
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

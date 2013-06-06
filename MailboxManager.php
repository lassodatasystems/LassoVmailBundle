<?php

namespace Lasso\VmailBundle;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Exception;
use FilesystemIterator;
use Lasso\VmailBundle\Entity\Mailbox;
use Lasso\VmailBundle\Exception\VmailException;
use Lasso\VmailBundle\Repository\DomainRepository;
use Lasso\VmailBundle\Repository\EmailRepository;
use Lasso\VmailBundle\Repository\MailboxRepository;
use Monolog\Logger;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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
     * @param string $localPart
     * @param string $password
     * @param string $domainString
     * @param int    $quota
     * @param bool   $noHash
     *
     * @throws Exception
     *
     * @return Mailbox
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
            $mailbox->setMaildir($this->getMailDirPath($localPart, $domain->getName()));
            $mailbox->setQuota($quota);

            $this->aliasManager->createAlias($email->getEmail(), $email->getEmail());

            $this->em->persist($mailbox);

            $this->em->flush();
            $this->em->commit();

            return $mailbox;
        } catch (Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @param string $username
     * @param int    $quota
     *
     * updates a users mail quota, quota passed is expected in GB
     *
     * @throws VmailException
     */
    public function updateQuota($username, $quota)
    {
        /** @var $mailbox Mailbox */
        $mailbox = $this->mailboxRepository->findOneBy(['username' => $username]);
        if ($mailbox) {
            $mailbox->setQuota(($quota * pow(1024, 3))); //convert to bytes from GB
            $this->em->persist($mailbox);
            $this->em->flush();

        } else {
            throw VmailException::userNotFound($username);
        }
    }

    /**
     * @param string $username
     * @param        $password
     * @param bool   $hash
     *
     * @throws VmailException
     * updates a users password with a new password
     *
     */
    public function updatePassword($username, $password, $hash = false)
    {
        /** @var $mailbox Mailbox */
        $mailbox = $this->mailboxRepository->findOneBy(['username' => $username]);
        if ($mailbox) {
            $password = !$hash ? $this->hashPassword($password) : $password;
            $mailbox->setPassword($password); //must be a valid hash
            $this->em->persist($mailbox);
            $this->em->flush();

        } else {
            throw VmailException::userNotFound($username);
        }
    }

    /**
     * @param $username
     *
     * @throws VmailException
     */
    public function deleteMailbox($username)
    {
        /** @var $mailbox Mailbox */
        $mailbox = $this->mailboxRepository->findOneBy(['username' => $username]);
        if ($mailbox) {
            $this->deleteMailDirectory($mailbox->getMaildir());
            $this->em->remove($mailbox->getEmail());
            $this->em->remove($mailbox);
            $this->em->flush();

        } else {
            throw VmailException::userNotFound($username);
        }
    }

    /**
     * @param string $directoryPath
     *
     * @return bool
     */
    protected function deleteMailDirectory($directoryPath)
    {
        /** @var $paths RecursiveDirectoryIterator[] */
        if (is_dir($directoryPath)) {
            $paths = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directoryPath, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($paths as $path) {
                $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
            }
            rmdir($directoryPath);
        }

        return true;
    }

    /**
     * @param string $username
     * @param string $domain
     *
     * @return string
     */
    private function getMailDirPath($username, $domain)
    {
        $maildir = $this->rootMailDir . '/';
        $maildir .= $domain . '/';
        $maildir .= $username[0] . '/';
        $maildir .= $username[1] . '/';
        $maildir .= $username[2] . '/';
        $maildir .= $username . time();

        return $maildir;
    }

    /**
     * @param string $password
     * @param string $noHash
     *
     * @return string
     *
     * @throws VmailException
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

    /**
     * @param $username
     *
     * @return bool
     */
    private function isValidUserName($username)
    {
        return strlen($username) >= 3;
    }
}

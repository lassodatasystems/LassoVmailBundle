<?php

namespace Lasso\VmailBundle\Unit\Tests;

use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Exception\VmailException;
use Lasso\VmailBundle\MailboxManager;
use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStream;

/**
 * Class MailboxManagerTest
 *
 * @package Lasso\VmailBundle\Unit\Tests
 */
class MailboxManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function createValidMailbox()
    {
        $username = 'travis';
        $domain   = 'test.com';

        $email = new Email();
        $email->setEmail($username . '@' . $domain);

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['persist', 'beginTransaction', 'flush', 'commit'], [], '', false);
        $mockEm->expects($this->atLeastOnce())
            ->method('persist');
        $mockEm->expects($this->once())
            ->method('beginTransaction');
        $mockEm->expects($this->once())
            ->method('flush');
        $mockEm->expects($this->once())
            ->method('commit');

        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy', 'getUnManagedEntities', 'getEntityManager'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('findOneBy')
            ->will($this->returnValue(false));
        $mockEmailRepo->expects($this->once())
            ->method('getUnManagedEntities')
            ->will($this->returnValue([]));
        $mockEmailRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEm));

        $mockDomain     = $this->getMock('\Lasso\VmailBundle\Entity\Domain');
        $mockDomainRepo = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->once())
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['noMocks'], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', ['createAlias'], [], '', false);
        $mockAliasManager->expects($this->once())
            ->method('createAlias');

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger, 2147483648, '/vmstore/vmail');
        $mailbox        = $mailboxManager->createMailbox($username, 'password', $domain);

        $this->assertInstanceOf('Lasso\VmailBundle\Entity\Mailbox', $mailbox);
    }

    /**
     * @test
     */
    public function createValidMailboxWithNoHash()
    {
        $username = 'travis';
        $domain   = 'test.com';

        $email = new Email();
        $email->setEmail($username . '@' . $domain);

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);

        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy', 'getUnManagedEntities', 'getEntityManager'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('findOneBy')
            ->will($this->returnValue(false));
        $mockEmailRepo->expects($this->once())
            ->method('getUnManagedEntities')
            ->will($this->returnValue([]));
        $mockEmailRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEm));

        $mockDomain     = $this->getMock('\Lasso\VmailBundle\Entity\Domain');
        $mockDomainRepo = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->once())
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['noMocks'], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', ['createAlias'], [], '', false);
        $mockAliasManager->expects($this->once())
            ->method('createAlias');

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger, 2147483648, '/vmstore/vmail');
        $mailbox        = $mailboxManager->createMailbox($username, '$1$513e4eec$WmathViLZNVh8FoOYWMmh/', $domain, 2147483648, true);

        $this->assertTrue($mailbox->getPassword() == '$1$513e4eec$WmathViLZNVh8FoOYWMmh/');
        $this->assertInstanceOf('Lasso\VmailBundle\Entity\Mailbox', $mailbox);
    }

    /**
     * @test
     */
    public function createMailboxWithNoHashAndAnInvalidHash()
    {
        $username = 'travis';
        $domain   = 'test.com';

        $email = new Email();
        $email->setEmail($username . '@' . $domain);

        $this->setExpectedException('\Lasso\VmailBundle\Exception\VmailException');

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);

        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy', 'getUnManagedEntities', 'getEntityManager'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('findOneBy')
            ->will($this->returnValue(false));
        $mockEmailRepo->expects($this->once())
            ->method('getUnManagedEntities')
            ->will($this->returnValue([]));
        $mockEmailRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEm));

        $mockDomain     = $this->getMock('\Lasso\VmailBundle\Entity\Domain');
        $mockDomainRepo = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->once())
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['noMocks'], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger, 2147483648, '/vmstore/vmail');
        $mailbox        = $mailboxManager->createMailbox($username, '$1513e4eec$WmathViLZNVh8FoOYWMmh/', $domain, 2147483648, true);

        $this->assertInstanceOf('Lasso\VmailBundle\Entity\Mailbox', $mailbox);
    }

    /**
     * @test
     */
    public function mailboxCreationFailsWhenEmailExists()
    {
        $username = 'travis';
        $domain   = 'test.com';

        $this->setExpectedException('Lasso\VmailBundle\Exception\VmailException');

        $email = new Email();
        $email->setEmail($username . '@' . $domain);

        $mockEm        = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);
        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy'], [], '', false);
        $mockEmailRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($email));

        $mockDomainRepo  = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', [], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger, 2147483648, '/vmstore/vmail');
        $mailboxManager->createMailbox($username, 'password', $domain);

    }

    /**
     * @test
     */
    public function createMailboxWithInvalidEmail()
    {
        $this->setExpectedException('Lasso\VmailBundle\Exception\VmailException');

        $mockEm        = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);
        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['exists'], [], '', false);
        $mockEmailRepo->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));

        $mockDomainRepo  = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', [], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $mailboxManager->createMailbox('travis', 'password', 'test');

    }

    /**
     * @test
     */
    public function createMailboxWithInvalidUsername()
    {
        $this->setExpectedException('Lasso\VmailBundle\Exception\VmailException');

        $mockEm        = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);
        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['exists'], [], '', false);
        $mockEmailRepo->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(false));

        $mockDomainRepo  = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', [], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $mailboxManager->createMailbox('tr', 'password', 'test.com');

    }

    /**
     * @test
     */
    public function updateQuotaWithValidUsername()
    {

        $username = 'travis';

        $mockMailbox = $this->getMock('\Lasso\VmailBundle\Entity\Mailbox', ['setQuota']);
        $mockMailbox->expects($this->once())
            ->method('setQuota');

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['persist', 'flush'], [], '', false);
        $mockEm->expects($this->once())
            ->method('persist');
        $mockEm->expects($this->once())
            ->method('flush');

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);
        $mockDomainRepo   = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo    = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', [], [], '', false);
        $mockMailboxRepo  = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['findOneBy'], [], '', false);
        $mockMailboxRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($mockMailbox));
        $mockLogger = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $mailboxManager->updateQuota($username, '2');
    }


    /**
     * @test
     */
    public function updatePasswordWithValidHash()
    {
        $username = 'travis';
        $password = '$1$513e4eec$WmathViLZNVh8FoOYWMmh/';

        $mockMailbox = $this->getMock('\Lasso\VmailBundle\Entity\Mailbox', ['setPassword']);
        $mockMailbox->expects($this->once())
            ->method('setPassword');

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['persist', 'flush'], [], '', false);
        $mockEm->expects($this->once())
            ->method('persist');
        $mockEm->expects($this->once())
            ->method('flush');

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);
        $mockDomainRepo   = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo    = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', [], [], '', false);
        $mockMailboxRepo  = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['findOneBy'], [], '', false);
        $mockMailboxRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($mockMailbox));
        $mockLogger = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $mailboxManager->updatePassword($username, $password);
    }

    /**
     * @test
     */
    public function updateQuotaWithNonExistentUser()
    {

        $username = 'travis';

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['persist', 'flush'], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);
        $mockDomainRepo   = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo    = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', [], [], '', false);
        $mockMailboxRepo  = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['findOneBy'], [], '', false);
        $mockMailboxRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue([]));
        $mockLogger = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $this->assertFalse($mailboxManager->updateQuota($username, '2'));
    }


    /**
     * @test
     */
    public function updatePasswordWithNonExistentUser()
    {
        $username = 'travis';

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['persist', 'flush'], [], '', false);
        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);
        $mockDomainRepo   = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo    = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', [], [], '', false);
        $mockMailboxRepo  = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['findOneBy'], [], '', false);
        $mockMailboxRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue([]));
        $mockLogger = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $this->assertFalse($mailboxManager->updatePassword($username, 'test'));
    }

    /**
     * @test
     */
    public function deleteMailbox()
    {
        $structure = [
            'vmstore' => [
                'vmail' => [
                    'test.com' => [
                        't' => [
                            'r' => [
                                'a' => ['mail.txt']
                            ]
                        ]
                    ]
                ]
            ]
        ];
        vfsStream::setup('root', null, $structure);

        $username = 'travis';

        $mockMailbox = $this->getMock('\Lasso\VmailBundle\Entity\Mailbox', ['getMaildir']);
        $mockMailbox->expects($this->once())
            ->method('getMaildir')
            ->will($this->returnValue('vfs://root'));

        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['remove', 'flush'], [], '', false);
        $mockEm->expects($this->exactly(2))
            ->method('remove');
        $mockEm->expects($this->once())
            ->method('flush');

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);
        $mockDomainRepo   = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo    = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', [], [], '', false);
        $mockMailboxRepo  = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['findOneBy'], [], '', false);
        $mockMailboxRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($mockMailbox));
        $mockLogger = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $mailboxManager->deleteMailbox($username);
    }

    /**
     * @test
     */
    public function deleteMailboxWhenUserNotFound()
    {
        $mockEm = $this->getMock('\Doctrine\ORM\EntityManager', ['remove', 'flush'], [], '', false);
        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', [], [], '', false);
        $mockDomainRepo   = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo    = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', [], [], '', false);
        $mockMailboxRepo  = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['findOneBy'], [], '', false);
        $mockMailboxRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(false));
        $mockLogger = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger);
        $this->assertFalse($mailboxManager->deleteMailbox('travis'));
    }
}

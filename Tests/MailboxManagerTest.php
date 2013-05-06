<?php

use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Exception\VmailException;
use Lasso\VmailBundle\MailboxManager;

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

        $mockEm        = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);
        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy','getUnManagedEntities'], [], '', false);
        $mockEmailRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(false));
        $mockEmailRepo->expects($this->once())
            ->method('getUnManagedEntities')
            ->will($this->returnValue([]));

        $mockDomain     = $this->getMock('\Lasso\VmailBundle\Entity\Domain');
        $mockDomainRepo = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->once())
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', [], [], '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', [], [], '', false);

        $mockAliasManager = $this->getMock('Lasso\VmailBundle\AliasManager', ['createAlias'], [], '', false);
        $mockAliasManager->expects($this->once())
            ->method('createAlias');

        $mailboxManager = new MailboxManager($mockEm, $mockAliasManager, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger, 2147483648, '/vmstore/vmail');
        $return         = $mailboxManager->createMailbox($username, 'password', $domain);

        $this->assertEquals($return, 0);
    }

    /**
     * @test
     */
    public function mailboxCreationFailsWhenEmailExists()
    {
        $username = 'travis';
        $domain   = 'test.com';

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
        $return         = $mailboxManager->createMailbox($username, 'password', $domain);

        $this->assertEquals($return, VmailException::ERROR_EMAIL_EXISTS);
    }

    /**
     * @test
     */
    public function createMailboxWithNoHashOption()
    {

    }

    /**
     * @test
     */
    public function createMailboxWithInvalidEmail()
    {
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
        $return         = $mailboxManager->createMailbox('travis', 'password', 'test');

        $this->assertEquals($return, VmailException::ERROR_INVALID_EMAIL);
    }

    /**
     * @test
     */
    public function createMailboxWithInvalidUsername()
    {
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
        $return         = $mailboxManager->createMailbox('tr', 'password', 'test.com');

        $this->assertEquals($return, VmailException::ERROR_INVALID_USERNAME);
    }
}

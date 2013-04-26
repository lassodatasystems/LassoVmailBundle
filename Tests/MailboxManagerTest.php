<?php

use Lasso\VmailBundle\Exception\VmailException;
use Lasso\VmailBundle\MailboxManager;

class MailboxManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function mailboxCreationFailsWhenEmailExists()
    {

        $username = 'travis';
        $domain   = 'test.com';

        $mockEm        = $this->getMock('\Doctrine\ORM\EntityManager', array(), array(), '', false);
        $mockAliasRepo = $this->getMock('\Lasso\VmailBundle\Repository\AliasRepository', array(), array(), '', false);
        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', array(), array(), '', false);
        $mockEmailRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($username . '@' . $domain));
        $mockDomainRepo  = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', array(), array(), '', false);
        $mockMailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', array(), array(), '', false);
        $mockLogger      = $this->getMock('\Monolog\Logger', array(), array(), '', false);

        $mailboxManager = new MailboxManager($mockEm, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockMailboxRepo, $mockLogger, 2147483648, '/vmstore/vmail');
        $return         = $mailboxManager->createMailbox($username, 'password', $domain);

        $this->assertEquals($return, VmailException::ERROR_EMAIL_EXISTS);
    }

    /**
     * @test
     */
    public function createMailboxWithNoHashOption()
    {

    }
}

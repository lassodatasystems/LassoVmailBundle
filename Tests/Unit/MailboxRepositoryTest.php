<?php

namespace Lasso\VmailBundle\Tests\Unit;

use PHPUnit_Framework_TestCase;

/**
 * Class MailboxRepositoryTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class MailboxRepositoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getMailboxReturnsNewMailbox()
    {
        $mailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['none'], [], '', false);
        $mailbox     = $mailboxRepo->getMailbox('travis');

        $this->assertInstanceOf('\Lasso\VmailBundle\Entity\Mailbox', $mailbox);
    }

    /**
     * @test
     */
    public function getMailboxWithInvalidUsernameThrowsVmailException()
    {
        $username = 'ta';

        $this->setExpectedException('Lasso\VmailBundle\Exception\VmailException');

        $mailboxRepo = $this->getMock('\Lasso\VmailBundle\Repository\MailboxRepository', ['none'], [], '', false);
        $mailboxRepo->getMailbox($username);
    }
}

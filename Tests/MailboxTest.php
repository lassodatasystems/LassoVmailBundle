<?php

namespace Lasso\VmailBundle\Tests;

use Lasso\VmailBundle\Entity\Mailbox;
use PHPUnit_Framework_TestCase;

class MailboxTest extends PHPUnit_Framework_TestCase
{

    use AccessorTest;

    /**
     * @test
     */
    public function accessors()
    {
        $mailbox = new Mailbox();
        $this->accessorTest($mailbox);
    }
}

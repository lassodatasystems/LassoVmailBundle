<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Entity\Mailbox;
use PHPUnit_Framework_TestCase;

/**
 * Class MailboxTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
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

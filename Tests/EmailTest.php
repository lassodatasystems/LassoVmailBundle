<?php

namespace Lasso\VmailBundle\Tests;

use Lasso\VmailBundle\Entity\Email;
use PHPUnit_Framework_TestCase;

class EmailTest extends PHPUnit_Framework_TestCase{

    use AccessorTest;

    /**
     * @test
     */
    public function accessors()
    {
        $email = new Email();
        $this->accessorTest($email);
    }
}

<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Entity\Email;
use PHPUnit_Framework_TestCase;

/**
 * Class EmailTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class EmailTest extends PHPUnit_Framework_TestCase
{

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

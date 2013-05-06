<?php

namespace Lasso\VmailBundle\Tests;

use Lasso\VmailBundle\Entity\Domain;
use PHPUnit_Framework_TestCase;

class DomainTest extends PHPUnit_Framework_TestCase{

    use AccessorTest;

    /**
     * @test
     */
    public function accessors()
    {
        $domain = new Domain();
        $this->accessorTest($domain);
    }
}

<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Entity\Domain;
use PHPUnit_Framework_TestCase;

/**
 * Class DomainTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class DomainTest extends PHPUnit_Framework_TestCase
{

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

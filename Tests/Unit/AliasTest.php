<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Entity\Alias;
use PHPUnit_Framework_TestCase;

/**
 * Class AliasTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class AliasTest extends PHPUnit_Framework_TestCase
{

    use AccessorTest;

    /**
     * @test
     */
    public function accessors()
    {
        $alias = new Alias();
        $this->accessorTest($alias);
    }
}

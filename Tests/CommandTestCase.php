<?php
namespace Lasso\VmailBundle\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * This test case is used to test symfony commands
 */
abstract class CommandTestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @return Container
     */
    protected function getContainer()
    {
        $kernel    = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $container = new Container();
        $container->set('kernel', $kernel);

        return $container;
    }
}

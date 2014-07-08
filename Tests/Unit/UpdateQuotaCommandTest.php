<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Command\UpdateQuotaCommand;
use Lasso\VmailBundle\Tests\CommandTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateQuotaCommandTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class UpdateQuotaCommandTest extends CommandTestCase
{

    /**
     * @todo: complete test
     */
    public function updateValidUsersQuota()
    {
        $container = $this->getContainer();

        $em = $this->getMock('Doctrine\ORM\EntityManager', ['persist', 'flush'], [], '', false);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $mockMailboxManager = $this->getMock('\Lasso\VmailBundle\MailboxManager');

        $container->set('lasso_vmail.mailbox_manager', $mockMailboxManager);

        /** @var $cmd UpdateQuotaCommand */
        $cmd = $this->getMock('Lasso\VmailBundle\Command\UpdateQuotaCommand', ['dummy']);

        $cmd->setContainer($container);
        $tester = new CommandTester($cmd);
        $this->assertEquals(0, $tester->execute(array(
            'username' => 'travis',
            'quota'    => '2000'
        )));
    }

    /**
     * @test
     */
    public function updateQuotaFailsWhenUserNotFound()
    {

    }
}

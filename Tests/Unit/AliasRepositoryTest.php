<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Entity\Email;
use PHPUnit_Framework_TestCase;

class AliasRepositoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getAliasReturnsNewAlias()
    {
        $email = new Email();

        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findOneBy'], [], '', false);

        $mockAliasRepo->expects($this->once())
            ->method('findOneBy');

        $alias = $mockAliasRepo->getAlias($email, $email);

        $this->assertInstanceOf('Lasso\VmailBundle\Entity\Alias', $alias);

    }

    /**
     * @test
     */
    public function existingAliasIsReturned()
    {
        $email = new Email();

        $mockAlias = $this->getMock('Lasso\VmailBundle\Entity\Alias');

        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findOneBy'], [], '', false);

        $mockAliasRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($mockAlias));

        $bool = $mockAliasRepo->aliasExists($email, $email);

        $this->assertTrue($bool);
    }
}

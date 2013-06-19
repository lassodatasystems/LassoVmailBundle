<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\AliasManager;
use Lasso\VmailBundle\Entity\Alias;
use Lasso\VmailBundle\Entity\Domain;
use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Exception\VmailException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class AliasManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function createAliasFromNewEmails()
    {
        $mockDomain = $this->getMock('Lasso\VmailBundle\Entity\Domain');
        $mockEmail = $this->getMock('Lasso\VmailBundle\Entity\Email', ['getDomain']);
        $mockEmail->expects($this->exactly(2))
            ->method('getDomain')
            ->will($this->returnValue(false));

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', [], [], '', false);

        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->exactly(2))
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['getEmail'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('getEmail')
            ->will($this->returnValue($mockEmail));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $return = $aliasManager->createAlias('travis@test.com', 'travis@test.com');

        $this->assertEquals(0, $return);
    }

    /**
     * @test
     */
    public function creatingAliasFromCliThrowsErrors()
    {
        $this->setExpectedException('Lasso\VmailBundle\Exception\VmailException');

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', [], [], '', false);
        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['none'], [], '', false);
        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $aliasManager->createAlias('travistest.com', 'travistest.com');
    }

    /**
     * @test
     */
    public function createAliasFromInvalidEmails()
    {
        $mockDomain = $this->getMock('Lasso\VmailBundle\Entity\Domain');
        $mockEmail = $this->getMock('Lasso\VmailBundle\Entity\Email', ['getDomain']);
        $mockEmail->expects($this->exactly(2))
            ->method('getDomain')
            ->will($this->returnValue(false));

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', [], [], '', false);

        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->exactly(2))
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['getEmail'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('getEmail')
            ->will($this->returnValue($mockEmail));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $return = $aliasManager->createAlias('travis@test.com', 'travis@test.com');

        $this->assertEquals(0, $return);
    }

    /**
     * @test
     */
    public function createAliasFromExistingEmails()
    {
        $mockDomain = $this->getMock('Lasso\VmailBundle\Entity\Domain');
        $mockEmail = $this->getMock('Lasso\VmailBundle\Entity\Email', ['getDomain', 'getId']);
        $mockEmail->expects($this->exactly(2))
            ->method('getDomain')
            ->will($this->returnValue(false));
        $mockEmail->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(1));

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', [], [], '', false);

        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);
        $mockDomainRepo->expects($this->exactly(2))
            ->method('getDomain')
            ->will($this->returnValue($mockDomain));

        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['getEmail'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('getEmail')
            ->will($this->returnValue($mockEmail));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $return = $aliasManager->createAlias('travis@test.com', 'travis@test.com');

        $this->assertEquals(0, $return);
    }


    /**
     * @test
     */
    public function createAliasWithoutACycle()
    {
        $mockEmail1 = $this->mockEmail('travis@test.com', 1, 1);
        $mockEmail2 = $this->mockEmail('hawk@test.com', 2, 1);

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);

        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findBy','getAlias', 'aliasExists'], [], '', false);
        $mockAliasRepo->expects($this->once())
            ->method('findBy')
            ->will($this->returnValue([]));
        $mockAliasRepo->expects($this->once())
            ->method('getAlias');
        $mockAliasRepo->expects($this->once())
            ->method('aliasExists')
            ->will($this->returnValue(false));

        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);

        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['getEmail'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('getEmail')
            ->will($this->onConsecutiveCalls($mockEmail1, $mockEmail2));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);

        $return = $aliasManager->createAlias('travis@test.com', 'hawk@test.com');

        $this->assertEquals($return, 0);
    }

    /**
     * @test
     */
    public function createAliasWithACycle()
    {
        $mockEmail1 = $this->mockEmail('travis@test.com', 1, 5, 1, 2);
        $mockEmail2 = $this->mockEmail('hawk@test.com', 2, 2, 1, 2);
        $mockEmail3 = $this->mockEmail('sock@test.com', 3, 1, 0, 2);

        $this->setExpectedException('Lasso\VmailBundle\Exception\VmailException');

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);

        $mockAlias0 = $this->getMock('Lasso\VmailBundle\Entity\Alias', ['none']);
        $mockAlias0->setSource($mockEmail2);
        $mockAlias0->setDestination($mockEmail3);

        $mockAlias1 = $this->getMock('Lasso\VmailBundle\Entity\Alias', ['none']);
        $mockAlias1->setSource($mockEmail3);
        $mockAlias1->setDestination($mockEmail1);

        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findBy', 'aliasExists'], [], '', false);
        $mockAliasRepo->expects($this->exactly(2))
            ->method('findBy')
            ->will($this->onConsecutiveCalls([$mockAlias0], [$mockAlias1]));
        $mockAliasRepo->expects($this->once())
            ->method('aliasExists')
            ->will($this->returnValue(false));

        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', ['getDomain'], [], '', false);

        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['getEmail'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('getEmail')
            ->will($this->onConsecutiveCalls($mockEmail1, $mockEmail2));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);

        $aliasManager->createAlias('travis@test.com', 'hawk@test.com');
    }

    /**
     * @test
     */
    public function deleteAlias(){

        $email1 = 'travis@test.com';
        $email2 = 'hawk@test.com';

        $mockEmail1 = $this->mockEmail($email1, 1, 0, 0, 0);
        $mockEmail2 = $this->mockEmail($email2, 2, 0, 0, 0);

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);

        /** @var $mockAlias Alias */
        $mockAlias = $this->getMock('Lasso\VmailBundle\Entity\Alias', ['none']);
        $mockAlias->setSource($mockEmail1);
        $mockAlias->setDestination($mockEmail2);

        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findOneBy'], [], '', false);
        $mockAliasRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($mockAlias));

        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy'], [], '', false);
        $mockEmailRepo->expects($this->atLeastOnce())
            ->method('findOneBy')
            ->will($this->onConsecutiveCalls($mockEmail1, $mockEmail2));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $aliasManager->deleteAliasByValue($email1, $email2);
    }


    /**
     * @test
     */
    public function deleteAliasForNonExistentSource(){

        $this->setExpectedException('\Lasso\VmailBundle\Exception\VmailException');

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findOneBy'], [], '', false);
        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('findOneBy')
            ->will($this->returnValue(false));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $aliasManager->deleteAliasByValue('travis@test.com', 'travis@test.com');
    }

    /**
     * @test
     */
    public function deleteAliasForNonExistentDestination(){

        $mockEmail = $this->mockEmail('hawk@test.com', 1, 0, 0, 0);

        $this->setExpectedException('\Lasso\VmailBundle\Exception\VmailException');

        $mockEntityManger = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);
        $mockAliasRepo = $this->getMock('Lasso\VmailBundle\Repository\AliasRepository', ['findOneBy'], [], '', false);
        $mockDomainRepo = $this->getMock('Lasso\VmailBundle\Repository\DomainRepository', [], [], '', false);
        $mockEmailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy'], [], '', false);
        $mockEmailRepo->expects($this->exactly(2))
            ->method('findOneBy')
            ->will($this->onConsecutiveCalls($mockEmail, false));

        $mockLogger = $this->getMock('Monolog\Logger', [], [], '', false);

        $aliasManager = new AliasManager($mockEntityManger, $mockAliasRepo, $mockDomainRepo, $mockEmailRepo, $mockLogger);
        $aliasManager->deleteAliasByValue('travis@test.com', 'travis@test.com');
    }

    /**
     * @param string $name
     * @param int    $id
     * @param Domain $domain
     *
     * @return Email|PHPUnit_Framework_MockObject_MockObject
     */
    private function mockEmail(
        $email,
        $id = 1,
        $expectedEmailCalls = 1,
        $expectedDomainCalls = 1,
        $expectedIdCalls = 1,
        $domain = null)
    {
        if(is_null($domain)) {
            $domain = $this->mockDomain('test.com');
        }

        $mockEmail = $this->getMock('Lasso\VmailBundle\Entity\Email', ['getDomain', 'getId', 'getEmail']);
        $mockEmail->expects($this->exactly($expectedEmailCalls))
            ->method('getEmail')
            ->will($this->returnValue($email));
        $mockEmail->expects($this->exactly($expectedDomainCalls))
            ->method('getDomain')
            ->will($this->returnValue($domain));
        $mockEmail->expects($this->exactly($expectedIdCalls))
            ->method('getId')
            ->will($this->returnValue($id));

        return $mockEmail;
    }

    /**
     * @param $domain
     *
     * @return Domain|PHPUnit_Framework_MockObject_MockObject
     */
    private function mockDomain($domain) {
        return $this->getMock('Lasso\VmailBundle\Entity\Domain');
    }
}

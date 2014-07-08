<?php

namespace Lasso\VmailBundle\Tests\Unit;

use PHPUnit_Framework_TestCase;

/**
 * Class DomainRepositoryTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class DomainRepositoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function creatingDuplicateDomainsArePreventedByUnManagedEntities()
    {

        $domainName = 'travis.com';

        $mockDomain = $this->getMock('\Lasso\VmailBundle\Entity\Domain', ['getName'], [], '', false);
        $mockDomain->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($domainName));

        $mockUnitOfWork = $this->getMock('\Doctrine\ORM\UnitOfWork', ['getScheduledEntityInsertions'], [], '', false);
        $mockUnitOfWork->expects($this->once())
            ->method('getScheduledEntityInsertions')
            ->will($this->returnValue([$mockDomain]));

        $mockEntityManager = $this->getMock('\Doctrine\ORM\EntityManager', ['getUnitOfWork'], [], '', false);
        $mockEntityManager->expects($this->once())
            ->method('getUnitOfWork')
            ->will($this->returnValue($mockUnitOfWork));

        $mockDomainRepo = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', ['getEntityManager'], [], '', false);
        $mockDomainRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEntityManager));

        $mockDomainRepo->getDomain($domainName);
    }

    /**
     * @test
     */
    public function createNewDomain()
    {

        $domainName = 'travis.com';

        $mockEntityManager = $this->getMock('\Doctrine\ORM\EntityManager', ['persist'], [], '', false);
        $mockEntityManager->expects($this->once())
            ->method('persist');

        $mockDomainRepo = $this->getMock('\Lasso\VmailBundle\Repository\DomainRepository', ['getUnManagedEntities', 'findOneBy', 'getEntityManager'], [], '', false);
        $mockDomainRepo->expects($this->once())
            ->method('getUnManagedEntities')
            ->will($this->returnValue([]));

        $mockDomainRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue([]));

        $mockDomainRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEntityManager));

        $mockDomainRepo->getDomain($domainName);
    }
}

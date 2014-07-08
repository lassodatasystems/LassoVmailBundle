<?php

namespace Lasso\VmailBundle\Tests\Unit;

use Lasso\VmailBundle\Entity\Email;
use PHPUnit_Framework_TestCase;

/**
 * Class EmailRepositoryTest
 *
 * @package Lasso\VmailBundle\Tests\Unit
 */
class EmailRepositoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getEmailReturnsANewEmail()
    {
        $email = new Email();

        $mockEntityManager = $this->getMock('Doctrine\ORM\EntityManager', ['persist'], [], '', false);
        $mockEntityManager->expects($this->once())
            ->method('persist')
            ->will($this->returnValue(''));

        $emailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy', 'getUnManagedEntities', 'getEntityManager'], [], '', false);
        $emailRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEntityManager));

        $emailRepo->expects($this->once())
            ->method('getUnManagedEntities')
            ->will($this->returnValue([]));

        $emailRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue(false));

        $emailRepo->getEmail('test@test.com');
    }

    /**
     * @test
     */
    public function creatingDuplicateEmailsArePreventedByUnManagedEntities()
    {

        $email = 'test@test.com';

        $mockEmail = $this->getMock('\Lasso\VmailBundle\Entity\Email', ['getEmail'], [], '', false);
        $mockEmail->expects($this->once())
            ->method('getEmail')
            ->will($this->returnValue($email));

        $mockUnitOfWork = $this->getMock('\Doctrine\ORM\UnitOfWork', ['getScheduledEntityInsertions'], [], '', false);
        $mockUnitOfWork->expects($this->once())
            ->method('getScheduledEntityInsertions')
            ->will($this->returnValue([$mockEmail]));

        $mockEntityManager = $this->getMock('\Doctrine\ORM\EntityManager', ['getUnitOfWork'], [], '', false);
        $mockEntityManager->expects($this->once())
            ->method('getUnitOfWork')
            ->will($this->returnValue($mockUnitOfWork));

        $mockEmailRepo = $this->getMock('\Lasso\VmailBundle\Repository\EmailRepository', ['getEntityManager'], [], '', false);
        $mockEmailRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEntityManager));

        $mockEmailRepo->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($mockEntityManager));

        $mockEmailRepo->getEmail($email);
    }
}

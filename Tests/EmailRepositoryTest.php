<?php
/**
 * Created by JetBrains PhpStorm.
 * User: andrew
 * Date: 4/26/13
 * Time: 3:38 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Lasso\VmailBundle\Tests;

use Lasso\VmailBundle\Entity\Email;
use PHPUnit_Framework_TestCase;

class EmailRepositoryTest extends PHPUnit_Framework_TestCase{

    /**
     * @test
     */
    public function getEmailReturnsANewEmail(){
        $email = new Email();
        $emailRepo = $this->getMock('Lasso\VmailBundle\Repository\EmailRepository', ['findOneBy'], [], '', false);
        $emailRepo->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($email));

        $emailRepo->getEmail('test@test.com');
    }
}

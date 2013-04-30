<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Domain;
use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Util\EmailParser;

/**
 * Class EmailRepository
 * @package Lasso\VmailBundle\Repository
 */
class EmailRepository extends EntityRepository
{

    /**
     * @param string Email
     *
     * @return Email
     */
    public function getEmail($emailString)
    {
        $parsedEmail = EmailParser::parseEmail($emailString);
        $email = $this->findOneBy(array('email' => $emailString));
        if (!$email) {
            $email = new Email();
            $email->setLocalPart($parsedEmail->localPart);
            $email->setEmail($emailString);
        }
        return $email;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function exists($email)
    {
        return $this->findOneBy(array('email' => $email)) ? true : false;
    }
}

<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\Common\Util\Debug;
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
        $email       = null;
        $parsedEmail = EmailParser::parseEmail($emailString);
        foreach ($this->getUnManagedEntities() as $entity) {
            if ($entity->getEmail() == $emailString) {
                $email = $entity;
                break;
            }
        }

        if (is_null($email)) {
            $email = $this->findOneBy(['email' => $emailString]);
            if (!$email) {
                $email = new Email();
                $email->setLocalPart($parsedEmail->localPart);
                $email->setEmail($emailString);
            }

            $this->getEntityManager()->persist($email);
        }

        return $email;
    }

    /**
     * @return Email[]
     */
    protected function getUnManagedEntities()
    {
        return array_filter($this->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions(), function ($e) {
            return ($e instanceof Email);
        });
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

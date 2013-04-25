<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Domain;
use Lasso\VmailBundle\Entity\Email;

/**
 * Class EmailRepository
 * @package Lasso\VmailBundle\Repository
 */
class EmailRepository extends EntityRepository
{

    /**
     * @param        $localPart
     * @param Domain $domain
     *
     * @return Email
     */
    public function getEmail($localPart, Domain $domain)
    {
        $email = $this->findOneBy(array('email' => $localPart . '@' . $domain->getName()));
        if (!$email) {
            $email = new Email();
            $email->setLocalPart($localPart);
            $email->setDomain($domain);
            $email->setEmail($localPart . '@' . $domain->getName());
            return $email;
        } else {
            $email;
        }
    }
}

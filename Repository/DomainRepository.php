<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Domain;

/**
 * Class DomainRepository
 * @package Lasso\VmailBundle\Repository
 */
class DomainRepository extends EntityRepository
{

    /**
     * @param string $name
     *
     * @return Domain
     */
    public function getDomain($name)
    {
        $domain = $this->findOneBy(array('name' => $name));
        if (!$domain) {
            $domain = new Domain();
            $domain->setName($name);
        }
        return $domain;
    }
}

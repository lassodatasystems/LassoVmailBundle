<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\Common\Util\Debug;
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
        $domain = null;

        foreach($this->getUnManagedEntities() as $entity) {
            if($entity->getName() == $name) {
                $domain = $entity;
                break;
            }
        }

        if(is_null($domain)) {
            $domain = $this->findOneBy(array('name' => $name));

            if (!$domain) {
                $domain = new Domain();
                $domain->setName($name);
            }

            $this->getEntityManager()->persist($domain);
        }

        return $domain;
    }

    /**
     * @return Domain[]
     */
    protected function getUnManagedEntities()
    {
        return array_filter($this->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions(), function ($e) {
            return ($e instanceof Domain);
        });
    }
}

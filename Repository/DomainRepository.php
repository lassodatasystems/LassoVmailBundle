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
     * @param string $search
     * @param bool   $limit
     * @param bool   $offset
     * @param array  $sort
     *
     * @return Domain[]
     */
    public function getList($search = '', $limit = false, $offset = false, $sort = [])
    {
        $qb = $this->createQueryBuilder('d');
        $qb->leftJoin('d.local', 'l');
        if ($search) {
            $qb->where("d.name LIKE :search");
            $qb->setParameter('search', "%$search%");
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if (!empty($sort->property)) {
            $sortColumns = ['domain'=> 'd.name',
                            'id'    => 'd.id'
            ];

            $qb->orderBy($sortColumns[$sort->property], $sort->direction);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $search
     *
     * @return int
     */
    public function getCount($search = '')
    {
        return count($this->getList($search));
    }

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

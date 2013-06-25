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
}

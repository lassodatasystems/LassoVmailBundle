<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Alias;
use Lasso\VmailBundle\Entity\Email;

/**
 * Class AliasRepository
 * @package Lasso\VmailBundle\Repository
 */
class AliasRepository extends EntityRepository
{
    /**
     * @param Email $source
     * @param Email $destination
     *
     * @return Alias|object
     */
    public function getAlias(Email $source, Email $destination)
    {
        $alias = $this->findOneBy(array('source' => $source->getEmail(), 'destination' => $destination->getEmail()));
        if (!$alias) {
            $alias = new Alias();
            $alias->setSource($source);
            $alias->setDestination($destination);
        }

        return $alias;
    }

    /**
     * @param Email $source
     * @param Email $destination
     *
     * @return bool
     */
    public function aliasExists(Email $source, Email $destination)
    {
        return $this->findOneBy(array('source' => $source->getEmail(), 'destination' => $destination->getEmail())) ? true : false;
    }

    /**
     * @return Alias[]
     */
    public function getAliases($search = '', $limit = false, $offset = false){
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.source', 's');
        $qb->leftJoin('a.destination', 'd');
        if($search){
            $qb->where("s.email LIKE :sourceEmail");
            $qb->orWhere("d.email LIKE :destinationEmail");
            $qb->setParameter('sourceEmail', "%$search%");
            $qb->setParameter('destinationEmail', "%$search%");
        }
        if($offset){
            $qb->setFirstResult($offset);
        }
        if($limit){
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @return int count
     */
    public function getAliasCount($search = ''){
        $aliases = $this->getAliases($search);
        return count($aliases);
    }
}

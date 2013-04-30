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
}

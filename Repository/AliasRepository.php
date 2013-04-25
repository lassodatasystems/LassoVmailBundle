<?php

namespace Lasso\VmailBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lasso\VmailBundle\Entity\Alias;
use Lasso\VmailBundle\Entity\Email;

class AliasRepository extends EntityRepository
{

    public function getAlias(Email $source, Email $destination)
    {
        $alias = $this->findOneBy(array('source' => $source->getEmail(), 'destination' => $destination->getEmail()));
        if (!$alias) {
            $alias = new Alias();
            $alias->setSource($source);
            $alias->setDestination($destination);
            return $alias;
        } else {
            return $alias;
        }
    }
}

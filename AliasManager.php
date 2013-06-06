<?php

namespace Lasso\VmailBundle;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use Exception;
use Lasso\VmailBundle\Entity\Alias;
use Lasso\VmailBundle\Entity\Email;
use Lasso\VmailBundle\Exception\VmailException;
use Lasso\VmailBundle\Repository\AliasRepository;
use Lasso\VmailBundle\Repository\DomainRepository;
use Lasso\VmailBundle\Repository\EmailRepository;
use Lasso\VmailBundle\Util\EmailParser;
use Monolog\Logger;

/**
 * Class AliasManager
 * @package Lasso\VmailBundle
 */
class AliasManager
{

    /**
     * @param EntityManager    $em
     * @param AliasRepository  $aliasRepository
     * @param DomainRepository $domainRepository
     * @param EmailRepository  $emailRepository
     * @param Logger           $logger
     */
    public function __construct(EntityManager $em,
                                AliasRepository $aliasRepository,
                                DomainRepository $domainRepository,
                                EmailRepository $emailRepository,
                                Logger $logger)
    {
        $this->em               = $em;
        $this->aliasRepository  = $aliasRepository;
        $this->domainRepository = $domainRepository;
        $this->emailRepository  = $emailRepository;
        $this->logger           = $logger;
    }

    /**
     * @param $sourceString
     * @param $destinationString
     *
     * @throws VmailException
     * @throws Exception
     * @return int|mixed
     */
    public function createAlias($sourceString, $destinationString)
    {
        $alias = null;
        $this->em->beginTransaction();
        try {
            $source      = $this->emailRepository->getEmail($sourceString);
            $destination = $this->emailRepository->getEmail($destinationString);

            if (!$this->aliasRepository->aliasExists($source, $destination)) {
                if (!$source->getDomain()) {
                    $parsedSource = EmailParser::parseEmail($sourceString);
                    $source->setDomain($this->domainRepository->getDomain($parsedSource->domainName));
                }

                if (!$destination->getDomain()) {
                    $parsedSource = EmailParser::parseEmail($destinationString);
                    $destination->setDomain($this->domainRepository->getDomain($parsedSource->domainName));
                }

                if ($this->needsAliasCycleCheck($source, $destination)) {
                    if ($this->hasStronglyConnectedComponents($source, $destination)) {
                        throw VmailException::aliasLoopDetected($source->getEmail(), $destination->getEmail());
                    }
                }
                $alias = $this->aliasRepository->getAlias($source, $destination);
                $this->em->persist($alias);
            }

            $this->em->flush();
            $this->em->commit();

            return $alias;
        } catch (Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @param Email $source
     * @param Email $destination
     *
     * @return bool
     */
    private function needsAliasCycleCheck(Email $source, Email $destination)
    {
        if (is_null($source->getId()) || is_null($destination->getId())) { // new email don't need to be checked
            return false;
        }
        if ($source->getEmail() == $destination->getEmail()) { // an alias loop is valid if it points directly to itself
            return false;
        }

        return true;
    }

    /**
     * used to detect alias loops
     *
     * strongly connected components are those that return back to themselves when traversed
     *
     * @param Email $source
     * @param Email $destination
     *
     * @return bool
     */
    private function hasStronglyConnectedComponents(Email $source, Email $destination)
    {
        /** @var Alias[] $aliases */
        $aliases = $this->aliasRepository->findBy(array('source' => $destination));
        foreach ($aliases as $alias) {
            if ($alias->getSource()->getId() == $alias->getDestination()->getId()) { // continue if alias is self referencing to prevent infinite loop
                continue;
            } else if ($alias->getDestination()->getEmail() == $source->getEmail()) { // compare to start of traversal
                return true;
            } else {
                return $this->hasStronglyConnectedComponents($source, $alias->getDestination()); // continue traversing
            }
        }

        return false;
    }

    /**
     * @param int $aliasId
     *
     * @return bool
     * @throws VmailException
     */
    public function deleteAlias($aliasId)
    {
        $alias = $this->aliasRepository->find($aliasId);
        $this->em->remove($alias);
        $this->em->flush();

        return true;
    }

    /**
     * @param string $sourceEmail
     * @param string $destinationEmail
     *
     * @return bool
     * @throws VmailException
     */
    public function deleteAliasByValue($sourceEmail, $destinationEmail)
    {
        $source      = $this->emailRepository->findOneBy(['email' => $sourceEmail]);
        $destination = $this->emailRepository->findOneBy(['email' => $destinationEmail]);

        if (!$source) {
            throw VmailException::emailNotFound($sourceEmail);
        }
        if (!$destination) {
            throw VmailException::emailNotFound($destinationEmail);
        }

        $alias = $this->aliasRepository->findOneBy(['source' => $source, 'destination' => $destination]);
        $this->em->remove($alias);
        $this->em->flush();

        return true;
    }
}

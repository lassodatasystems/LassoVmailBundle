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

class AliasManager
{

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

    public function createAlias($sourceString, $destinationString)
    {
        $this->em->beginTransaction();
        try {
            $source      = $this->emailRepository->getEmail($sourceString);
            $destination = $this->emailRepository->getEmail($destinationString);

            if (!$source->getDomain()) {
                $parsedSource = EmailParser::parseEmail($sourceString);
                $source->setDomain($this->domainRepository->getDomain($parsedSource->domainName));
            }

            if (!$destination->getDomain()) {
                $parsedSource = EmailParser::parseEmail($destinationString);
                $destination->setDomain($this->domainRepository->getDomain($parsedSource->domainName));
            }

            $alias = $this->aliasRepository->getAlias($source, $destination);

            $this->em->persist($alias);

            $this->em->flush();
            $this->em->commit();

            return 0;
        } catch (VmailException $e) {
            $this->logger->err($e->getMessage());
            $this->em->rollback();
            $this->em->close();

            return $e->getCode();
        } catch (Exception $e) {
            $this->logger->err($e->getMessage());
            $this->em->rollback();
            $this->em->close();

            return 2;
        }
    }

    /**
     * @param $source
     * @param $destination
     *
     * @return bool
     */
    private function checkForCycle($source, $aliasDestination)
    {
        //@todo implement this
        return false;
    }
}

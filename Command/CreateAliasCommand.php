<?php

namespace Lasso\VmailBundle\Command;

use Lasso\VmailBundle\AliasManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateMailCommand
 * @package Lasso\VmailBundle\Command
 */
class CreateAliasCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:create-alias')
            ->setDescription('create a alias')
            ->addArgument('source', InputArgument::REQUIRED, 'the incoming email address')
            ->addArgument('destination', InputArgument::REQUIRED, 'the location the email will be sent to');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $aliasManager AliasManager */
        $aliasManager = $this->getContainer()->get('lasso_vmail.alias_manager');
        return $aliasManager->createAlias($input->getArgument('source'), $input->getArgument('destination'));
    }
}

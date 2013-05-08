<?php

namespace Lasso\VmailBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateQuotaCommand
 * @package Lasso\VmailBundle\Command
 */
class UpdateQuotaCommand {

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:create-alias')
            ->setDescription('update quota command')
            ->addArgument('username', InputArgument::REQUIRED, '')
            ->addArgument('quota', InputArgument::REQUIRED, 'the new quota in MB');
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
        $aliasManager = $this->getContainer()->get('lasso_vmail.mailbox_manager');
        return $aliasManager->updateQuota($input->getArgument('source'), $input->getArgument('destination'));
    }
}

<?php

namespace Lasso\VmailBundle\Command;

use Lasso\VmailBundle\AliasManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteAliasCommand extends ContainerAwareCommand {

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:delete-alias')
            ->setDescription('deletes a alias')
            ->addArgument('source', InputArgument::REQUIRED, 'alias source email')
            ->addArgument('destination', InputArgument::REQUIRED, 'alias destionation email');
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
        return $aliasManager->deleteAlias($input->getArgument('source'), $input->getArgument('destination'));
    }
}

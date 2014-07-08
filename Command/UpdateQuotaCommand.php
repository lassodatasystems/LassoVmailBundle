<?php

namespace Lasso\VmailBundle\Command;

use Lasso\VmailBundle\MailboxManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateQuotaCommand
 *
 * @package Lasso\VmailBundle\Command
 */
class UpdateQuotaCommand extends ContainerAwareCommand
{

    /**
     * Configuration
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:update-quota')
            ->setDescription('updates a users mailbox quota')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('quota', InputArgument::REQUIRED, 'the new quota in GB');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $mailboxManager MailboxManager */
        $mailboxManager = $this->getContainer()->get('lasso_vmail.mailbox_manager');

        return $mailboxManager->updateQuota($input->getArgument('username'), $input->getArgument('quota'));
    }
}

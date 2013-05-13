<?php

namespace Lasso\VmailBundle\Command;

use Lasso\VmailBundle\MailboxManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateQuotaCommand
 * @package Lasso\VmailBundle\Command
 */
class UpdatePasswordCommand extends ContainerAwareCommand {

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:update-password')
            ->setDescription('updates a users mailbox quota')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED, 'new password')
            ->addOption('no-hash', 'P', InputOption::VALUE_NONE, 'if set password will not be hashed, use to preserve hashes from other systems');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $mailboxManager MailboxManager */
        $mailboxManager = $this->getContainer()->get('lasso_vmail.mailbox_manager');
        $return = $mailboxManager->updatePassword($input->getArgument('username'), $input->getArgument('password'), $input->getOption('no-hash'));

        $output->writeln("<info>Password updated was successfully.</info>");

        return $return;
    }
}

<?php

namespace Lasso\VmailBundle\Command;

use Lasso\VmailBundle\MailboxManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateMailCommand
 * @package Lasso\VmailBundle\Command
 */
class CreateMailboxCommand extends ContainerAwareCommand
{

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:create-mailbox')
            ->setDescription('create a new user for the mail system')
            ->addArgument('username', InputArgument::REQUIRED, 'username of the user you are creating')
            ->addArgument('password', InputArgument::REQUIRED, 'password for the mailbox, see no-hash option')
            ->addArgument('domain', InputArgument::REQUIRED, 'domain for the mailbox')
            ->addArgument('quota', InputArgument::OPTIONAL, 'mailbox size in bytes')
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
        $quota = $input->getArgument('quota') != '' ? $input->getArgument('quota') : 0;
        /** @var $mailboxManager MailboxManager */
        $mailboxManager = $this->getContainer()->get('lasso_vmail.mailbox_manager');

        $mailboxManager->createMailbox($input->getArgument('username'), $input->getArgument('password'), $input->getArgument('domain'), $quota, $input->getOption('no-hash'));
        $output->writeln("<info>Mailbox was successfully created</info>");

        return 0;
    }
}

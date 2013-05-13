<?php

namespace Lasso\VmailBundle\Command;

use Lasso\VmailBundle\MailboxManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteMailboxCommand extends ContainerAwareCommand {

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('lasso:vmail:delete-mailbox')
            ->setDescription('deletes a mailbox and its mail from the file system')
            ->addArgument('username', InputArgument::REQUIRED, 'username of the mailbox being deleted');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $aliasManager MailboxManager */
        $mailboxManager = $this->getContainer()->get('lasso_vmail.mailbox_manager');
        $return = $mailboxManager->deleteMailbox($input->getArgument('username'));

        $output->writeln("<info>Mailbox deleted.</info>");

        return $return;
    }
}

<?php

namespace Lasso\VmailBundle\Exception;

use Exception;

/**
 * VmailException exception class
 */
class VmailException extends Exception
{
    const ERROR_EMAIL_EXISTS = 1;

    /**
     * @param $email
     *
     * @return VmailException
     */
    public static function emailExists($email)
    {
        return new self("Unable to create mailbox: {$email}, emails or alias already in use.", self::ERROR_EMAIL_EXISTS);
    }
}

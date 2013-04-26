<?php

namespace Lasso\VmailBundle\Exception;

use Exception;

/**
 * VmailException exception class
 */
class VmailException extends Exception
{
    /**
     * Return code for email exists
     */
    const ERROR_EMAIL_EXISTS = 1;

    /**
     * Return code for invalid username
     */
    const ERROR_INVALID_USERNAME = 1;

    /**
     * @param $email
     *
     * @return VmailException
     */
    public static function emailExists($email)
    {
        return new self("Unable to create mailbox: {$email}, emails or alias already in use.", self::ERROR_EMAIL_EXISTS);
    }

    /**
     * @param $username
     *
     * @return VmailException
     */
    public static function invalidUsername($username)
    {
        return new self("Invalid username: {$username}", self::ERROR_INVALID_USERNAME);
    }
}

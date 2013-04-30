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
    const ERROR_EMAIL_EXISTS = 1001;

    /**
     * Return code for invalid username
     */
    const ERROR_INVALID_USERNAME = 1002;

    /**
     * Return code for invalid email
     */
    const ERROR_INVALID_EMAIL = 1003;

    /**
     * @param $email
     *
     * @return VmailException
     */
    public static function emailExists($email)
    {
        return new self("Unable to create mailbox: {$email}, email or alias already in use.", self::ERROR_EMAIL_EXISTS);
    }

    /**
     * @param $username
     *
     * @return VmailException
     */
    public static function invalidUsername($username)
    {
        return new self("Invalid username: {$username}, username must be 3 or more characters", self::ERROR_INVALID_USERNAME);
    }

    /**
     * @param $email
     *
     * @return VmailException
     */
    public static function invalidEmail($email)
    {
        return new self("Unable to create mailbox: {$email}, supplied email is invalid.", self::ERROR_INVALID_EMAIL);
    }
}

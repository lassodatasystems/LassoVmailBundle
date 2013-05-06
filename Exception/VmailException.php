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
    const ERROR_EMAIL_EXISTS = 3;

    /**
     * Return code for invalid username
     */
    const ERROR_INVALID_USERNAME = 4;

    /**
     * Return code for invalid email
     */
    const ERROR_INVALID_EMAIL = 5;

    /**
     * Return code for alias loop
     */
    const ERROR_ALIAS_LOOP = 6;

    /**
     * Return code for alias
     */
    const ERROR_CREATING_ALIAS = 7;

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
     * @param string $email
     *
     * @return VmailException
     */
    public static function invalidEmail($email)
    {
        return new self("Unable to create mailbox: {$email}, supplied email is invalid.", self::ERROR_INVALID_EMAIL);
    }

    /**
     * @param string $source
     * @param string $destination
     *
     * @return VmailException
     */
    public static function aliasLoopDetected($source, $destination)
    {
        return new self("Unable to create alias: {$source} -> {$destination}, alias loop detected", self::ERROR_ALIAS_LOOP);
    }

    /**
     *
     * @return VmailException
     */
    public static function invalidAlias()
    {
        return new self("Unable to create alias", self::ERROR_CREATING_ALIAS);
    }
}

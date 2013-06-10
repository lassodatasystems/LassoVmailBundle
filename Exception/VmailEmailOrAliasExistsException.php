<?php

namespace Lasso\VmailBundle\Exception;

use Lasso\VmailBundle\Exception\VmailException;

/**
 * VmailEmailOrAliasExistsException exception class
 */
class VmailEmailOrAliasExistsException extends VmailException
{
    /**
     * Error code for email exists
     */
    const ERROR_EMAIL_EXISTS = 3;

    /**
     * Error code for invalid username
     */
    const ERROR_INVALID_USERNAME = 4;

    /**
     * Error code for invalid email
     */
    const ERROR_INVALID_EMAIL = 5;

    /**
     * Error code for alias loop
     */
    const ERROR_ALIAS_LOOP = 6;

    /**
     * Error code for invalid hash
     */
    const ERROR_INVALID_HASH = 7;

    /**
     * Error code for user not found
     */
    const ERROR_USER_NOT_FOUND = 8;

    /**
     * Error code for email not found
     */
    const ERROR_EMAIL_NOT_FOUND = 9;

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
     * @param string $hash
     *
     * @return VmailException
     */
    public static function invalidPasswordHash($hash)
    {
        return new self("Supplied password hash is invalid: {$hash}.", self::ERROR_INVALID_HASH);
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
     * @param string $username
     *
     * @return VmailException
     */
    public static function userNotFound($username)
    {
        return new self("User not found: {$username}", self::ERROR_USER_NOT_FOUND);
    }

    /**
     * @param string $email
     *
     * @return VmailException
     */
    public static function emailNotFound($email)
    {
        return new self("Email not found: {$email}", self::ERROR_EMAIL_NOT_FOUND);
    }
}

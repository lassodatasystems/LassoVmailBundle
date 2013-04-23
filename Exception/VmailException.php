<?php

namespace Lasso\VmailBundle\Exception;

use Exception;

/**
 * VmailException exception class
 */
class VmailException extends Exception
{
    /**
     * @param $username
     *
     * @return VmailException
     */
    public static function invalidUsername($username)
    {
        return new self("Username: {$username} is not valid");
    }

    /**
     * @param $email
     *
     * @return VmailException
     */
    public static function emailExists($email)
    {
        return new self("Unable to create mailbox: {$email}, emails or alias already in use.");
    }

    /**
     * @param $hash
     *
     * @return VmailException
     */
    public static function invalidPasswordHash($hash)
    {
        return new self("Password Hash: {$hash} is not valid for this system.");
    }
}

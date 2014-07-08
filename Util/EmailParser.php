<?php

namespace Lasso\VmailBundle\Util;

use Lasso\VmailBundle\Exception\VmailException;
use stdClass;

/**
 * Class EmailParser
 *
 * @package Lasso\VmailBundle\Util
 */
class EmailParser
{

    /**
     * @param string $email
     *
     * @return ParsedEmail
     * @throws VmailException
     */
    public static function parseEmail($email)
    {
        $atPos = strpos($email, '@');
        if (!$atPos) {
            throw VmailException::invalidEmail($email);
        }

        return new ParsedEmail(substr($email, 0, $atPos), substr($email, $atPos+1));
    }
}

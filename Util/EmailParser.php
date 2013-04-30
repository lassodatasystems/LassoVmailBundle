<?php

namespace Lasso\VmailBundle\Util;

use stdClass;

/**
 * Class EmailParser
 * @package Lasso\VmailBundle\Util
 */
class EmailParser
{

    /**
     * @param $email
     *
     * @return ParsedEmail
     */
    public static function parseEmail($email)
    {
        $atPos = strpos($email, '@');
        return new ParsedEmail(substr($email, 0, $atPos), substr($email, $atPos+1));
    }
}

/**
 * Class ParsedEmail
 * @package Lasso\VmailBundle\Util
 */
class ParsedEmail
{
    /**
     * @var string
     */
    public $localPart = '';

    /**
     * @var string
     */
    public $domainName = '';

    /**
     * @param string $localPart
     * @param string $domainName
     */
    public function __construct($localPart, $domainName)
    {
        $this->localPart = $localPart;
        $this->domainName = $domainName;
    }
}

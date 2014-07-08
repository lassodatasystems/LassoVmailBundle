<?php

namespace Lasso\VmailBundle\Util;

/**
 * Class ParsedEmail
 *
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
        $this->localPart  = $localPart;
        $this->domainName = $domainName;
    }
}

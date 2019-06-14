<?php

namespace Nails\MailChimp\Resource\MailChimpList;

use Nails\Common\Resource;

/**
 * Class Member
 *
 * @package Nails\MailChimp\Resource
 */
class Member extends Resource
{
    public $id;
    public $email_type;
    public $email_address;
    public $status;

    // --------------------------------------------------------------------------

    /**
     * Returns the subscriber hash
     *
     * @return string
     */
    public function getSubscriberHash(): string
    {
        return md5(strtolower($this->email_address));
    }
}

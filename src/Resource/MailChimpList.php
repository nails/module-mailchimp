<?php

namespace Nails\MailChimp\Resource;

use Nails\Common\Resource;
use Nails\MailChimp\Factory\Api\Client;

/**
 * Class MailChimpList
 *
 * @package Nails\MailChimp\Resource
 */
class MailChimpList extends Resource
{
    /**
     * The Client which created the Resource
     *
     * @var Client
     */
    protected $oClient;

    // --------------------------------------------------------------------------

    /**
     * Sets the client
     *
     * @param Client $oClient
     *
     * @return $this
     */
    public function setClient(Client $oClient): MailChimpList
    {
        $this->oClient = $oClient;
        return $this;
    }
}

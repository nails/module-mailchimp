<?php

namespace Nails\MailChimp\Resource;

use Nails\Common\Resource;
use Nails\MailChimp\Factory\Api\Client;
use Nails\MailChimp\Factory\Api\Lists\Member;

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

    /**
     * @var Member
     */
    protected $oMember;

    // --------------------------------------------------------------------------

    public $id;
    public $name;

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

    // --------------------------------------------------------------------------

    /**
     * Sets the member instance
     *
     * @param Member $oMember
     *
     * @return $this
     */
    public function setMember(Member $oMember): MailChimpList
    {
        $this->oMember = $oMember;
        $this->oMember->setList($this);
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * @return Member
     */
    public function members(): Member
    {
        return $this->oMember;
    }
}

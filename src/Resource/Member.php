<?php

/**
 * MailChimp Member Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource;

use Nails\Common\Exception\FactoryException;
use Nails\Common\Resource\DateTime;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Factory\Tag;
use Nails\MailChimp\Service\Client;

/**
 * Class Member
 *
 * @package Nails\MailChimp\Resource
 */
class Member extends \Nails\Common\Resource
{
    /** @var Client Client */
    protected $oClient;

    /** @var Audience Client */
    protected $oAudience;

    /** @var string */
    public $id;

    /** @var string */
    public $email_address;

    /** @var string */
    public $unique_email_id;

    /** @var int */
    public $web_id;

    /** @var string */
    public $email_type;

    /** @var string */
    public $status;

    /** @var Member\MergeFields */
    public $merge_fields;

    /** @var Member\Stats */
    public $stats;

    /** @var string */
    public $ip_signup;

    /** @var DateTime */
    public $timestamp_signup;

    /** @var string */
    public $ip_opt;

    /** @var DateTime */
    public $timestamp_opt;

    /** @var int */
    public $member_rating;

    /** @var DateTime */
    public $last_changed;

    /** @var string */
    public $language;

    /** @var bool */
    public $vip;

    /** @var string */
    public $email_client;

    /** @var Member\Location */
    public $location;

    /** @var string */
    public $source;

    /** @var int */
    public $tags_count;

    /** @var Member\Tag[] */
    public $tags;

    /** @var string */
    public $list_id;

    /** @var Member\Link[] */
    public $_links;

    // --------------------------------------------------------------------------

    /**
     * Member constructor.
     *
     * @param array    $mObj
     * @param Client   $oClient
     * @param Audience $oAudience
     *
     * @throws FactoryException
     */
    public function __construct($mObj, Client $oClient, Audience $oAudience)
    {
        parent::__construct($mObj);

        $this->oClient   = $oClient;
        $this->oAudience = $oAudience;

        $this->merge_fields     = Factory::resource('MemberMergeFields', Constants::MODULE_SLUG, $this->merge_fields);
        $this->stats            = Factory::resource('MemberStats', Constants::MODULE_SLUG, $this->stats);
        $this->location         = Factory::resource('MemberLocation', Constants::MODULE_SLUG, $this->location);
        $this->tags             = array_map(
            function ($aTag) {
                return Factory::resource('MemberTag', Constants::MODULE_SLUG, $aTag);
            },
            $this->tags
        );
        $this->_links           = array_map(
            function ($aLink) {
                return Factory::resource('MemberLink', Constants::MODULE_SLUG, $aLink);
            },
            $this->_links
        );
        $this->timestamp_signup = !empty($this->timestamp_signup)
            ? Factory::resource('DateTime', null, ['raw' => $this->timestamp_signup])
            : null;
        $this->timestamp_opt    = !empty($this->timestamp_opt)
            ? Factory::resource('DateTime', null, ['raw' => $this->timestamp_opt])
            : null;
        $this->last_changed     = !empty($this->last_changed)
            ? Factory::resource('DateTime', null, ['raw' => $this->last_changed])
            : null;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the member's audience
     *
     * @return Audience
     */
    public function getAudience(): Audience
    {
        return $this->oAudience;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the subscriber hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return \Nails\MailChimp\Factory\Member::buildSubscriberHash($this->email_address);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a configured instance of the tag factory
     *
     * @return Tag
     * @throws FactoryException
     */
    public function tags(): Tag
    {
        /** @var Tag $oTagFactory */
        $oTagFactory = Factory::factory('Tag', Constants::MODULE_SLUG, $this->oClient, $this);
        return $oTagFactory;
    }
}


<?php

/**
 * MailChimp Audience Resource
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
use Nails\MailChimp\Resource;
use Nails\MailChimp\Service\Client;

/**
 * Class Audience
 *
 * @package Nails\MailChimp\Resource
 */
class Audience extends \Nails\Common\Resource
{
    /** @var Client */
    protected $oClient;

    /** @var string */
    public $id;

    /** @var int */
    public $web_id;

    /** @var string */
    public $name;

    /** @var Resource\Audience\Contact */
    public $contact;

    /** @var string */
    public $permission_reminder;

    /** @var bool */
    public $use_archive_bar;

    /** @var Resource\Audience\CampaignDefaults */
    public $campaign_defaults;

    /** @var string */
    public $notify_on_subscribe;

    /** @var string */
    public $notify_on_unsubscribe;

    /** @var DateTime */
    public $date_created;

    /** @var int */
    public $list_rating;

    /** @var bool */
    public $email_type_option;

    /** @var string */
    public $subscribe_url_short;

    /** @var string */
    public $subscribe_url_long;

    /** @var string */
    public $beamer_address;

    /** @var string */
    public $visibility;

    /** @var bool */
    public $double_optin;

    /** @var bool */
    public $has_welcome;

    /** @var bool */
    public $marketing_permissions;

    /** @var Resource\Audience\Module[] */
    public $modules;

    /** @var Resource\Audience\Stats */
    public $stats;

    /** @var Resource\Audience\Link[] */
    public $_links;

    // --------------------------------------------------------------------------

    /**
     * Audience constructor.
     *
     * @param array  $mObj
     * @param Client $oClient
     *
     * @throws FactoryException
     */
    public function __construct($mObj, Client $oClient)
    {
        parent::__construct($mObj);

        $this->oClient = $oClient;

        $this->contact           = Factory::resource('AudienceContact', Constants::MODULE_SLUG, $this->contact);
        $this->campaign_defaults = Factory::resource('AudienceCampaignDefaults', Constants::MODULE_SLUG, $this->campaign_defaults);
        $this->stats             = Factory::resource('AudienceStats', Constants::MODULE_SLUG, $this->stats);
        $this->modules           = array_map(
            function ($aModule) {
                return Factory::resource('AudienceModule', Constants::MODULE_SLUG, $aModule);
            },
            $this->modules
        );
        $this->_links            = array_map(
            function ($aLink) {
                return Factory::resource('AudienceLink', Constants::MODULE_SLUG, $aLink);
            },
            $this->_links
        );
        $this->date_created      = !empty($this->date_created)
            ? Factory::resource('DateTime', null, ['raw' => $this->date_created])
            : null;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a configured instance of the member factory
     *
     * @return \Nails\MailChimp\Factory\Member
     * @throws FactoryException
     */
    public function members(): \Nails\MailChimp\Factory\Member
    {
        /** @var \Nails\MailChimp\Factory\Member $oMemberFactory */
        $oMemberFactory = Factory::factory('Member', Constants::MODULE_SLUG, $this->oClient, $this);
        return $oMemberFactory;
    }
}


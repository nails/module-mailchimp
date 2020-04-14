<?php

/**
 * MailChimp Audience\CampaignDefaults Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Audience;

use Nails\Common\Resource;

/**
 * Class CampaignDefaults
 *
 * @package Nails\MailChimp\Resource\Audience
 */
class CampaignDefaults extends Resource
{
    /** @var string */
    public $from_name;

    /** @var string */
    public $from_email;

    /** @var string */
    public $subject;

    /** @var string */
    public $language;
}


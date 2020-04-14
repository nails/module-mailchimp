<?php

/**
 * MailChimp Member\Stats Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Member;

/**
 * Class Stats
 *
 * @package Nails\MailChimp\Resource\Member
 */
class Stats extends \Nails\Common\Resource
{
    /** @var int */
    public $avg_open_rate;

    /** @var int */
    public $avg_click_rate;
}


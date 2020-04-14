<?php

/**
 * MailChimp Member\Location Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Member;

/**
 * Class Location
 *
 * @package Nails\MailChimp\Resource\Member
 */
class Location extends \Nails\Common\Resource
{
    /** @var int */
    public $latitude;

    /** @var int */
    public $longitude;

    /** @var int */
    public $gmtoff;

    /** @var int */
    public $dstoff;

    /** @var string */
    public $country_code;

    /** @var string */
    public $timezone;
}


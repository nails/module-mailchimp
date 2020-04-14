<?php

/**
 * MailChimp Audience\Contact Resource
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
 * Class Contact
 *
 * @package Nails\MailChimp\Resource\Audience
 */
class Contact extends Resource
{
    /** @var string */
    public $company;

    /** @var string */
    public $address1;

    /** @var string */
    public $address2;

    /** @var string */
    public $city;

    /** @var string */
    public $state;

    /** @var string */
    public $zip;

    /** @var string */
    public $country;

    /** @var string */
    public $phone;
}


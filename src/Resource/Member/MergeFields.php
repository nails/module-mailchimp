<?php

/**
 * MailChimp Member\MergeFields Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Member;

/**
 * Class MergeFields
 *
 * @package Nails\MailChimp\Resource\Member
 */
class MergeFields extends \Nails\Common\Resource
{
    /** @var string */
    public $FNAME;

    /** @var string */
    public $LNAME;

    /** @var string */
    public $ADDRESS;

    /** @var string */
    public $PHONE;
}


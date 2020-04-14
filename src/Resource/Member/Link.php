<?php

/**
 * MailChimp Member\Link Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Member;

/**
 * Class Link
 *
 * @package Nails\MailChimp\Resource\Member
 */
class Link extends \Nails\Common\Resource
{
    /** @var string */
    public $rel;

    /** @var string */
    public $href;

    /** @var string */
    public $method;

    /** @var string */
    public $targetSchema;

    /** @var string */
    public $schema;
}


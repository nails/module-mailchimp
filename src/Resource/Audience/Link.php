<?php

/**
 * MailChimp Audience\Link Resource
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
 * Class Link
 *
 * @package Nails\MailChimp\Resource\Audience
 */
class Link extends Resource
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


<?php

/**
 * MailChimp Audience\Stats Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Audience;

use Nails\Common\Resource;
use Nails\Factory;

/**
 * Class Stats
 *
 * @package Nails\MailChimp\Resource\Audience
 */
class Stats extends Resource
{
    /** @var int */
    public $member_count;

    /** @var int */
    public $unsubscribe_count;

    /** @var int */
    public $cleaned_count;

    /** @var int */
    public $member_count_since_send;

    /** @var int */
    public $unsubscribe_count_since_send;

    /** @var int */
    public $cleaned_count_since_send;

    /** @var int */
    public $campaign_count;

    /** @var int */
    public $campaign_last_sent;

    /** @var int */
    public $merge_field_count;

    /** @var int */
    public $avg_sub_rate;

    /** @var int */
    public $avg_unsub_rate;

    /** @var int */
    public $target_sub_rate;

    /** @var int */
    public $open_rate;

    /** @var int */
    public $click_rate;

    /** @var Resource\DateTime */
    public $last_sub_date;

    /** @var Resource\DateTime */
    public $last_unsub_date;

    // --------------------------------------------------------------------------

    public function __construct($mObj = [])
    {
        parent::__construct($mObj);

        $this->last_sub_date   = !empty($this->last_sub_date)
            ? Factory::resource('DateTime', null, ['raw' => $this->last_sub_date])
            : null;
        $this->last_unsub_date = !empty($this->last_unsub_date)
            ? Factory::resource('DateTime', null, ['raw' => $this->last_unsub_date])
            : null;
    }
}

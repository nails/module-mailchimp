<?php

/**
 * MailChimp Member\Tag Resource
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Resource
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Resource\Member;

use Nails\Common\Exception\FactoryException;
use Nails\Common\Resource\DateTime;
use Nails\Factory;

/**
 * Class Tag
 *
 * @package Nails\MailChimp\Resource\Member
 */
class Tag extends \Nails\Common\Resource
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var DateTime */
    public $date_added;

    // --------------------------------------------------------------------------

    /**
     * Tag constructor.
     *
     * @param $resource
     *
     * @throws FactoryException
     */
    public function __construct(self|\stdClass|array $resource)
    {
        parent::__construct($resource);

        $this->date_added = !empty($this->date_added)
            ? Factory::resource('DateTime', null, ['raw' => $this->date_added])
            : null;
    }

}


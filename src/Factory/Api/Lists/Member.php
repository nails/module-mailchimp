<?php

namespace Nails\MailChimp\Factory\Api\Lists;

use Nails\MailChimp\Resource\MailChimpList;

/**
 * Class Member
 *
 * @package Nails\MailChimp\Factory\Api\Lists
 */
class Member
{
    public function getAll(): array
    {
        //  @todo (Pablo - 2019-06-13) - Complete this method
    }

    // --------------------------------------------------------------------------

    public function getById(string $sId): MailChimpList
    {
        //  @todo (Pablo - 2019-06-13) - Complete this method
    }

    // --------------------------------------------------------------------------

    public function create(array $aConfig): MailChimpList\Member
    {
        //  @todo (Pablo - 2019-06-13) - Complete this method
        //  @todo (Pablo - 2019-06-13) - Validate
        //  @todo (Pablo - 2019-06-13) - Use the FormValidation library when it is not dependent on CI
    }

    // --------------------------------------------------------------------------

    public function update(string $sId, array $aParameters = []): MailChimpList\Member
    {
        //  @todo (Pablo - 2019-06-13) - Complete this method
    }

    // --------------------------------------------------------------------------

    public function delete(string $sId): void
    {
        //  @todo (Pablo - 2019-06-13) - Complete this method
    }
}

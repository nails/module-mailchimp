<?php

namespace Nails\MailChimp\Factory\Api\Lists;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use Nails\MailChimp\Factory\Api\Client;
use Nails\MailChimp\Resource\MailChimpList;
use stdClass;

/**
 * Class Member
 *
 * @package Nails\MailChimp\Factory\Api\Lists
 */
class Member
{
    /**
     * The Client being used
     *
     * @var Client
     */
    protected $oClient;

    /**
     * The List being used
     *
     * @var MailChimpList
     */
    protected $oList;

    // --------------------------------------------------------------------------

    /**
     * Sets the client
     *
     * @param Client $oClient
     */
    public function setClient(Client $oClient): void
    {
        $this->oClient = $oClient;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->oClient;
    }

    // --------------------------------------------------------------------------

    /**
     * Sets the list
     *
     * @param MailChimpList $oList
     */
    public function setList(MailChimpList $oList): void
    {
        $this->oList = $oList;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the list
     *
     * @return MailChimpList
     */
    public function getList(): MailChimpList
    {
        return $this->oList;
    }

    // --------------------------------------------------------------------------

    /**
     * Lists all members
     *
     * @return MailChimpList\Member[]
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function getAll(): array
    {
        $oResponse = $this->getClient()
            ->get('/lists/' . $this->getList()->id . '/members');

        return array_map(function (stdClass $oMember) {

            /** @var MailChimpList\Member $oMember */
            $oMember = Factory::resource('ListMember', 'nails/module-mailchimp', $oMember);

            return $oMember;
        }, $oResponse->members);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a specific member
     *
     * @param string $sId The ID of the member
     *
     * @return MailChimpList\Member
     * @throws ApiException
     * @throws UnauthorisedException
     * @throws FactoryException
     */
    public function getById(string $sId): MailChimpList\Member
    {
        $oMember = $this->getClient()
            ->get('/lists/' . $this->getList()->id . '/members/' . $sId);

        /** @var MailChimpList\Member $oMember */
        $oMember = Factory::resource('ListMember', 'nails/module-mailchimp', $oMember);

        return $oMember;
    }

    // --------------------------------------------------------------------------

    /**
     * Creates a new member
     *
     * @param array $aParameters Parameters to create the member with
     *
     * @return MailChimpList\Member
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function create(array $aParameters): MailChimpList\Member
    {
        //  @todo (Pablo - 2019-06-13) - Validate
        //  @todo (Pablo - 2019-06-13) - Use the FormValidation library when it is not dependent on CI

        $oResponse = $this->getClient()
            ->post('/lists/' . $this->getList()->id . '/members', $aParameters);

        /** @var MailChimpList\Member $oResource */
        $oResource = Factory::resource('ListMember', 'nails/module-mailchimp', $oResponse);

        return $oResource;
    }

    // --------------------------------------------------------------------------

    public function update(string $sId, array $aParameters = []): MailChimpList\Member
    {
        $oResponse = $this->getClient()
            ->patch('/lists/' . $this->getList()->id . '/members/' . $sId, $aParameters);

        /** @var MailChimpList\Member $oResource */
        $oResource = Factory::resource('ListMember', 'nails/module-mailchimp', $oResponse);

        return $oResource;
    }

    // --------------------------------------------------------------------------

    public function delete(string $sId): void
    {
        $this->getClient()
            ->delete('/lists/' . $this->getList()->id . '/members/' . $sId);
    }
}

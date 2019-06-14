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
            ->get(
                $this->buildEndpoint()
            );

        return array_map(function (stdClass $oMember) {
            return $this->buildResource($oMember);
        }, $oResponse->members);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a specific member
     *
     * @param string $sEmail The email address of the member
     *
     * @return MailChimpList\Member
     * @throws ApiException
     * @throws UnauthorisedException
     * @throws FactoryException
     */
    public function getByEmail(string $sEmail): MailChimpList\Member
    {
        $oMember = $this->getClient()
            ->get(
                $this->buildEndpoint($sEmail)
            );

        return $this->buildResource($oMember);
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
            ->post(
                $this->buildEndpoint(),
                $aParameters
            );

        //  @todo (Pablo - 2019-06-14) - Catch API exceptions and convert into something more useful (e.g. user already exists)

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Updates a member
     *
     * @param string $sEmail      The email address of the member to update
     * @param array  $aParameters The parameters to update the list with
     *
     * @return MailChimpList\Member
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function update(string $sEmail, array $aParameters = []): MailChimpList\Member
    {
        $oResponse = $this->getClient()
            ->patch(
                $this->buildEndpoint($sEmail),
                $aParameters
            );

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Archives an existing member
     *
     * @param string $sEmail The email address of the member to archive
     *
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function archive(string $sEmail): void
    {
        $this->getClient()
            ->delete(
                $this->buildEndpoint($sEmail)
            );
    }

    // --------------------------------------------------------------------------

    /**
     * Unarchives (resubscribes) a member
     *
     * @param string $sEmail The email address of the member to unarchive
     *
     * @return MailChimpList\Member
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function unarchive(string $sEmail): MailChimpList\Member
    {
        return $this->update($sEmail, ['status' => 'subscribed']);
    }

    // --------------------------------------------------------------------------

    /**
     * Deletes an existing member
     *
     * @param string $sEmail The email address of the member to delete
     *
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function delete(string $sEmail): void
    {
        $this->getClient()
            ->post(
                $this->buildEndpoint($sEmail) . '/actions/delete-permanent'
            );
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the endpoint URL
     *
     * @param string $sEmail The email address of the resource
     *
     * @return string
     */
    protected function buildEndpoint(string $sEmail = null)
    {
        $sEndpoint = '/lists/' . $this->getList()->id . '/members';
        if (!empty($sEmail)) {
            $sEndpoint .= '/' . $this->buildSubscriberHash($sEmail);
        }
        return $sEndpoint;
    }

    // --------------------------------------------------------------------------

    /**
     * Builds a Member resource
     *
     * @param stdClass $oMember The member data
     *
     * @return MailChimpList\Member
     * @throws FactoryException
     */
    protected function buildResource(stdClass $oMember): MailChimpList\Member
    {
        /** @var MailChimpList\Member $oResource */
        $oResource = Factory::resource('ListMember', 'nails/module-mailchimp', $oMember);

        return $oResource;
    }

    // --------------------------------------------------------------------------

    /**
     * Builds a subscriber hash
     *
     * @param string $sEmail The email address of the member
     *
     * @return string
     */
    protected function buildSubscriberHash(string $sEmail): string
    {
        return md5(strtolower(trim($sEmail)));
    }
}

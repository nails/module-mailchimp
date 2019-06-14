<?php

namespace Nails\MailChimp\Factory\Api;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use Nails\MailChimp\Factory\Api\Lists\Member;
use Nails\MailChimp\Resource\MailChimpList;
use stdClass;

/**
 * Class Lists
 *
 * @package Nails\MailChimp\Api\Factory
 */
class Lists
{
    /**
     * The Client being used
     *
     * @var Client
     */
    protected $oClient;

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
     * The Member being used
     *
     * @var Member
     */
    protected $oMember;

    // --------------------------------------------------------------------------

    /**
     * Sets the client
     *
     * @param Member $oMember
     */
    public function setMember(Member $oMember): void
    {
        $this->oMember = $oMember;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the client
     *
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->oMember;
    }

    // --------------------------------------------------------------------------

    /**
     * Lists all lists
     *
     * @return MailChimpList[]
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function getAll(): array
    {
        $oResponse = $this->getClient()
            ->get(
                $this->buildEndpoint()
            );

        return array_map(function (stdClass $oList) {
            return $this->buildResource($oList);
        }, $oResponse->lists);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a specific list
     *
     * @param string $sId the ID of the list
     *
     * @return MailChimpList
     * @throws ApiException
     * @throws UnauthorisedException
     * @throws FactoryException
     */
    public function getById(string $sId): MailChimpList
    {
        $oResponse = $this->getClient()
            ->get(
                $this->buildEndpoint($sId)
            );

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Creates a new list
     *
     * @param array $aParameters Parameters to create the list with
     *
     * @return MailChimpList
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function create(array $aParameters): MailChimpList
    {
        //  @todo (Pablo - 2019-06-12) - Validate
        //  @todo (Pablo - 2019-06-12) - Use the FormValidation library when it is not dependent on CI

        $oResponse = $this->getClient()
            ->post(
                $this->buildEndpoint(),
                $aParameters
            );

        //  @todo (Pablo - 2019-06-14) - Catch API exceptions and convert into something more useful (e.g. list already exists)

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Updates a list
     *
     * @param string $sId         The ID of the list to update
     * @param array  $aParameters The parameters to update the list with
     *
     * @return MailChimpList
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function update(string $sId, array $aParameters = []): MailChimpList
    {
        $oResponse = $this->getClient()
            ->patch(
                $this->buildEndpoint($sId),
                $aParameters
            );

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Deletes an existing list
     *
     * @param string $sId The ID of the list to delete
     *
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function delete(string $sId): void
    {
        $this->getClient()
            ->delete(
                $this->buildEndpoint($sId)
            );
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the endpoint URL
     *
     * @param string $sId The ID of the resource
     *
     * @return string
     */
    protected function buildEndpoint(string $sId = null)
    {
        $sEndpoint = '/lists';
        if (!empty($sId)) {
            $sEndpoint .= '/' . $sId;
        }
        return $sEndpoint;
    }

    // --------------------------------------------------------------------------

    /**
     * Builds a List resource
     *
     * @param stdClass $oList The list data
     *
     * @return MailChimpList
     * @throws FactoryException
     */
    protected function buildResource(stdClass $oList): MailChimpList
    {
        /** @var MailChimpList $oResource */
        $oResource = Factory::resource('List', 'nails/module-mailchimp', $oList);
        $oResource->setClient($this->getClient());
        $oResource->setMember($this->getMember());

        return $oResource;
    }
}

<?php

namespace Nails\MailChimp\Factory\Api;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
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
     * Lists all lists
     *
     * @return MailChimpList[]
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function getAll(): array
    {
        $oResponse = $this->getClient()->get('/lists');
        return array_map(function (stdClass $oList) {

            /** @var MailChimpList $oList */
            $oList = Factory::resource('List', 'nails/module-mailchimp', $oList);
            $oList->setClient($this->getClient());

            return $oList;
        }, $oResponse->lists);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a specific list
     *
     * @param string $sId
     *
     * @return MailChimpList
     * @throws ApiException
     * @throws UnauthorisedException
     * @throws FactoryException
     */
    public function getById(string $sId): MailChimpList
    {
        $oList = $this->getClient()->get('/lists/' . $sId);

        /** @var MailChimpList $oList */
        $oList = Factory::resource('List', 'nails/module-mailchimp', $oList);
        $oList->setClient($this->getClient());

        return $oList;
    }

    // --------------------------------------------------------------------------

    /**
     * Creates a new list
     *
     * @return MailChimpList
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function create(array $aConfig): MailChimpList
    {
        //  @todo (Pablo - 2019-06-12) - Validate
        //  @todo (Pablo - 2019-06-12) - Use the FormValidation library when it is not dependent on CI

        $oResponse = $this->getClient()->post('/lists', $aConfig);

        /** @var MailChimpList $oResource */
        $oResource = Factory::resource('List', 'nails/module-mailchimp', $oResponse);
        $oResource->setClient($this->getClient());

        return $oResource;
    }

    // --------------------------------------------------------------------------

    /**
     * Updates a list
     *
     * @param string $sId         The list ID to update
     * @param array  $aParameters The values to set
     *
     * @return bool
     */
    public function update(string $sId, array $aParameters = []): bool
    {
        //  @todo (Pablo - 2019-06-12) - Update a list
    }

    // --------------------------------------------------------------------------

    /**
     * Deletes an existing list
     *
     * @param string $sId The list to delete
     *
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function delete(string $sId): void
    {
        $this->getClient()->delete('/lists/' . $sId);
    }
}

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
        $oResponse = $this->getClient()->get('/lists');
        return array_map(function (stdClass $oList) {

            /** @var MailChimpList $oList */
            $oList = Factory::resource('List', 'nails/module-mailchimp', $oList);
            $oList->setClient($this->getClient());
            $oList->setMember($this->getMember());

            return $oList;
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

        $oResponse = $this->getClient()->post('/lists', $aParameters);

        /** @var MailChimpList $oResource */
        $oResource = Factory::resource('List', 'nails/module-mailchimp', $oResponse);
        $oResource->setClient($this->getClient());
        $oResource->setMember($this->getMember());

        return $oResource;
    }

    // --------------------------------------------------------------------------

    /**
     * Updates a list
     *
     * @param string $sId
     * @param array  $aParameters
     *
     * @return MailChimpList
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function update(string $sId, array $aParameters = []): MailChimpList
    {
        $oResponse = $this->getClient()->patch('/lists/' . $sId, $aParameters);

        /** @var MailChimpList $oResource */
        $oResource = Factory::resource('List', 'nails/module-mailchimp', $oResponse);
        $oResource->setClient($this->getClient());

        return $oResource;
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

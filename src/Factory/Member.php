<?php

/**
 * MailChimp Member Factory
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Factory
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Factory;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Resource;
use Nails\MailChimp\Service\Client;
use stdClass;

/**
 * Class Member
 *
 * @package Nails\MailChimp\Factory
 */
class Member
{
    /**
     * The client to use
     *
     * @var Client
     */
    protected $oClient;

    /**
     * The audience to use
     *
     * @var Resource\Audience
     */
    protected $oAudience;

    // --------------------------------------------------------------------------

    /**
     * Member constructor.
     *
     * @param Client            $oClient   The client to use
     * @param Resource\Audience $oAudience The audience to use
     */
    public function __construct(Client $oClient, Resource\Audience $oAudience)
    {
        $this->oClient   = $oClient;
        $this->oAudience = $oAudience;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the configured audience
     *
     * @return Resource\Audience
     */
    public function getAudience(): Resource\Audience
    {
        return $this->oAudience;
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
        $sEndpoint = 'lists/' . $this->oAudience->id . '/members';
        if (!empty($sEmail)) {
            $sEndpoint .= '/' . $this->buildSubscriberHash($sEmail);
        }
        return $sEndpoint;
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the Member resource
     *
     * @param stdClass $oMember The member data
     *
     * @return Resource\Member
     * @throws FactoryException
     */
    protected function buildResource(stdClass $oMember): Resource\Member
    {
        /** @var Resource\Member $oResource */
        $oResource = Factory::resource('Member', Constants::MODULE_SLUG, $oMember, $this->oClient, $this->oAudience);
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
    public static function buildSubscriberHash(string $sEmail): string
    {
        return md5(strtolower(trim($sEmail)));
    }

    // --------------------------------------------------------------------------

    /**
     * Lists all members
     *
     * @return Resource\Member[]
     * @throws FactoryException
     * @throws ApiException
     */
    public function getAll(): array
    {
        $oResponse = $this->oClient
            ->get(
                $this->buildEndpoint()
            );

        return array_map(
            function (stdClass $oMember) {
                return $this->buildResource($oMember);
            },
            $oResponse->members
        );
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a specific member by their email address
     *
     * @param string $sEmail The email address of the member
     *
     * @return Resource\Member
     * @throws ApiException
     * @throws FactoryException
     */
    public function getByEmail(string $sEmail): Resource\Member
    {
        $oMember = $this->oClient
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
     * @return Resource\Member
     * @throws ApiException
     * @throws FactoryException
     */
    public function create(array $aParameters): Resource\Member
    {
        $oResponse = $this->oClient
            ->post(
                $this->buildEndpoint(),
                $aParameters
            );

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Updates an existing member
     *
     * @param string $sEmail      The email address of the member
     * @param array  $aParameters Parameters to update the member with
     *
     * @return Resource\Member
     * @throws ApiException
     * @throws FactoryException
     */
    public function update(string $sEmail, array $aParameters = []): Resource\Member
    {
        $oResponse = $this->oClient
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
     * @param string $sEmail The email address of the member
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function archive(string $sEmail): void
    {
        $this->oClient
            ->delete(
                $this->buildEndpoint($sEmail)
            );
    }

    // --------------------------------------------------------------------------

    /**
     * @param string $sEmail The email address of the member
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function unarchive(string $sEmail): void
    {
        $this->update($sEmail, ['status' => 'subscribed']);
    }

    // --------------------------------------------------------------------------

    /**
     * Deletes an existing member
     *
     * @param string $sEmail The email address of the member
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function delete(string $sEmail): void
    {
        $this->oClient
            ->post(
                $this->buildEndpoint($sEmail) . '/actions/delete-permanent'
            );
    }
}

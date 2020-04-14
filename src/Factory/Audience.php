<?php

/**
 * MailChimp Audience Factory
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
use Nails\MailChimp\Service\Client;
use Nails\MailChimp\Resource;
use stdClass;

/**
 * Class Audience
 *
 * @package Nails\MailChimp\Factory
 */
class Audience
{
    /**
     * The client to use
     *
     * @var Client
     */
    protected $oClient;

    // --------------------------------------------------------------------------

    /**
     * Audience constructor.
     *
     * @param Client $oClient
     */
    public function __construct(Client $oClient)
    {
        $this->oClient = $oClient;
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the endpoint URL
     *
     * @param string $sId The ID of the audience
     *
     * @return string
     */
    protected function buildEndpoint(string $sId = null)
    {
        return 'lists' . (!empty($sId) ? '/' . $sId : '');
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the Audience resource
     *
     * @param stdClass $oAudience the audience data
     *
     * @return Resource\Audience
     * @throws FactoryException
     */
    protected function buildResource(stdClass $oAudience): Resource\Audience
    {
        /** @var Resource\Audience $oResource */
        $oResource = Factory::resource('Audience', Constants::MODULE_SLUG, $oAudience, $this->oClient);
        return $oResource;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an array of all audiences
     *
     * @return Resource\Audience[]
     * @throws ApiException
     * @throws FactoryException
     */
    public function getAll(): array
    {
        $oResponse = $this->oClient
            ->get(
                $this->buildEndpoint()
            );

        return array_map(
            function (stdClass $oAudience) {
                return $this->buildResource($oAudience);
            },
            $oResponse->lists
        );
    }

    // --------------------------------------------------------------------------

    /**
     * Returns a specific audience
     *
     * @param string $sId The ID of the audience to return
     *
     * @return Resource\Audience
     * @throws ApiException
     * @throws FactoryException
     */
    public function getById(string $sId): Resource\Audience
    {
        $oResponse = $this->oClient
            ->get(
                $this->buildEndpoint($sId)
            );

        return $this->buildResource($oResponse);
    }

    // --------------------------------------------------------------------------

    /**
     * Creates an audience
     *
     * @param array $aParameters
     *
     * @return Resource\Audience
     * @throws FactoryException
     * @throws ApiException
     */
    public function create(array $aParameters): Resource\Audience
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
     * Updates an audience
     *
     * @param string $sId         The ID of the audience to update
     * @param array  $aParameters The parameters to update te audience with
     *
     * @return Resource\Audience
     * @throws ApiException
     * @throws FactoryException
     */
    public function update(string $sId, array $aParameters): Resource\Audience
    {
        return $this->buildResource(
            $this->oClient
                ->patch(
                    $this->buildEndpoint($sId),
                    $aParameters
                )
        );
    }

    // --------------------------------------------------------------------------

    /**
     * Deletes an audience
     *
     * @param string $sId The ID of the audience to delete
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function delete(string $sId): void
    {
        $this->oClient
            ->delete(
                $this->buildEndpoint($sId)
            );
    }
}

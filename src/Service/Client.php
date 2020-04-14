<?php

/**
 * MailChimp Client Service
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Service
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Service;

use Nails\Common\Exception\FactoryException;
use Nails\Common\Factory\HttpRequest;
use Nails\Config;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use Nails\MailChimp\Exception\Api\UnhandledHttpRequestException;
use Nails\MailChimp\Factory\Audience;
use Nails\MailChimp\Factory\Lists;
use Nails\MailChimp\Factory\Member;
use stdClass;

/**
 * Class Client
 *
 * @package Nails\MailChimp\Service
 */
class Client
{
    const DEFAULT_API_URL     = 'https://%s.api.mailchimp.com/%s/';
    const DEFAULT_DATA_CENTER = '';
    const DEFAULT_API_KEY     = '';
    const HTTP_METHOD_GET     = 'GET';
    const HTTP_METHOD_POST    = 'POST';
    const HTTP_METHOD_PATCH   = 'PATCH';
    const HTTP_METHOD_DELETE  = 'DELETE';

    // --------------------------------------------------------------------------

    /**
     * The API URL to use
     *
     * @var string
     */
    protected $sApiUrl;

    /**
     * The data center of the account
     *
     * @var string
     */
    protected $sDataCenter;

    /**
     * The API key
     *
     * @var string
     */
    protected $sApiKey;

    /**
     * The API version
     *
     * @var string
     */
    protected $sApiVersion = '3.0';

    // --------------------------------------------------------------------------

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->sApiUrl     = Config::get('MAILCHIMP_API_URL', static::DEFAULT_API_URL);
        $this->sDataCenter = Config::get('MAILCHIMP_DATA_CENTER', static::DEFAULT_DATA_CENTER);
        $this->sApiKey     = Config::get('MAILCHIMP_API_KEY', static::DEFAULT_API_KEY);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the API URL
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->sApiUrl;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the data center
     *
     * @return string
     */
    public function getDataCenter(): string
    {
        return $this->sDataCenter;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the API key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->sApiKey;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the API version
     *
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->sApiVersion;
    }

    // --------------------------------------------------------------------------

    /**
     * Calls the MailChimp API
     *
     * @param string $sMethod     The HTTP method
     * @param string $sEndPoint   The endpoint to call
     * @param array  $aParameters Any parameters to send
     *
     * @return stdClass|null
     * @throws ApiException
     * @throws FactoryException
     */
    public function call(string $sMethod, string $sEndPoint, array $aParameters = []): ?stdClass
    {
        $sUrl = sprintf(
            $this->getApiUrl(),
            $this->getDataCenter(),
            $this->getApiVersion()
        );

        switch ($sMethod) {
            case static::HTTP_METHOD_GET:
                /** @var HttpRequest\Get $oHttpRequest */
                $oHttpRequest = Factory::factory('HttpRequestGet');
                break;
            case static::HTTP_METHOD_POST:
                /** @var HttpRequest\Post $oHttpRequest */
                $oHttpRequest = Factory::factory('HttpRequestPost');
                break;
            case static::HTTP_METHOD_PATCH:
                /** @var HttpRequest\Patch $oHttpRequest */
                $oHttpRequest = Factory::factory('HttpRequestPatch');
                break;
            case static::HTTP_METHOD_DELETE:
                /** @var HttpRequest\Delete $oHttpRequest */
                $oHttpRequest = Factory::factory('HttpRequestDelete');
                break;
            default:
                throw new UnhandledHttpRequestException(
                    'HTTP "' . $sMethod . '" is not handled by this client'
                );
                break;
        }

        $oHttpRequest
            ->baseUri($sUrl)
            ->path($sEndPoint)
            ->auth('apiKey', $this->getApiKey());

        switch ($sMethod) {
            case static::HTTP_METHOD_POST:
            case static::HTTP_METHOD_PATCH:
                $oHttpRequest
                    ->body(json_encode($aParameters));
                break;
        }

        $oHttpResponse = $oHttpRequest->execute();
        $oResponse     = $oHttpResponse->getBody();
        $iReturnCode   = $oHttpResponse->getStatusCode();

        if ($iReturnCode === 401) {
            throw new UnauthorisedException(
                $oResponse->detail
            );
        } elseif ($iReturnCode >= 300) {
            $oException = new ApiException($oResponse->detail);
            if (!empty($oResponse->errors)) {
                $oException->setData($oResponse->errors);
            }
            throw $oException;
        }

        return $oResponse;
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a GET request
     *
     * @param string $sEndPoint   The endpoint to GET
     * @param array  $aParameters Any parameters to pass
     *
     * @return stdClass|null
     * @throws ApiException
     * @throws FactoryException
     */
    public function get(string $sEndPoint, array $aParameters = []): ?stdClass
    {
        return $this->call(static::HTTP_METHOD_GET, $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a POST request
     *
     * @param string $sEndPoint   The endpoint to POST
     * @param array  $aParameters Any parameters to pass
     *
     * @return stdClass|null
     * @throws ApiException
     * @throws FactoryException
     */
    public function post(string $sEndPoint, array $aParameters = []): ?stdClass
    {
        return $this->call(static::HTTP_METHOD_POST, $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a DELETE request
     *
     * @param string $sEndPoint   The endpoint to DELETE
     * @param array  $aParameters Any parameters to pass
     *
     * @return stdClass|null
     * @throws ApiException
     * @throws FactoryException
     */
    public function delete(string $sEndPoint, array $aParameters = []): ?stdClass
    {
        return $this->call(static::HTTP_METHOD_DELETE, $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a PATCH request
     *
     * @param string $sEndPoint   The endpoint to PATCH
     * @param array  $aParameters Any parameters to pass
     *
     * @return stdClass|null
     * @throws ApiException
     * @throws FactoryException
     */
    public function patch(string $sEndPoint, array $aParameters = []): ?stdClass
    {
        return $this->call(static::HTTP_METHOD_PATCH, $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the audience factory
     *
     * @return Audience
     * @throws FactoryException
     */
    public function audience(): Audience
    {
        /** @var Audience $oAudience */
        $oAudience = Factory::factory('Audience', Constants::MODULE_SLUG, $this);
        return $oAudience;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the members factory for a specific list
     *
     * @param string $sListId The ID of the list to return the members for
     *
     * @return Member
     * @throws ApiException
     * @throws FactoryException
     */
    public function members(string $sListId): Member
    {
        return $this->audience()
            ->getById($sListId)
            ->members();
    }
}

<?php

namespace Nails\MailChimp\Factory\Api;

use Nails\Common\Helper\ArrayHelper;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use stdClass;

/**
 * Class Client
 *
 * @package Nails\MailChimp\Api\Factory
 */
class Client
{
    const API_URL             = 'https://%s.api.mailchimp.com/%s/';
    const DEFAULT_DATA_CENTER = '';
    const DEFAULT_API_KEY     = '';
    const DEFAULT_API_VERSION = '3.0';

    // --------------------------------------------------------------------------

    /**
     * The instance of the lists class
     *
     * @var Lists
     */
    protected $oListsInstance;

    // --------------------------------------------------------------------------

    /**
     * The data center of the account
     *
     * @var string
     */
    private $sDataCenter;

    /**
     * The API key
     *
     * @var string
     */
    private $sApiKey;

    /**
     * The API version
     *
     * @var string
     */
    private $sApiVersion;

    // --------------------------------------------------------------------------

    /**
     * Client constructor.
     *
     * @param array $aConfig        The config array
     * @param Lists $oListsInstance The instance of the lists class
     */
    public function __construct(array $aConfig = [], Lists $oListsInstance = null)
    {
        if (is_null($oListsInstance)) {
            $this->oListsInstance = new Lists();
        }
        $this->oListsInstance->setClient($this);

        // --------------------------------------------------------------------------

        //  @todo (Pablo - 2019-06-12) - Default to database values?
        $this->sDataCenter = (string) ArrayHelper::getFromArray('data_center', $aConfig, static::DEFAULT_DATA_CENTER);
        $this->sApiKey     = (string) ArrayHelper::getFromArray('api_key', $aConfig, static::DEFAULT_API_KEY);
        $this->sApiVersion = (string) ArrayHelper::getFromArray('api_version', $aConfig, static::DEFAULT_API_VERSION);
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
     * @return stdClass
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function call(string $sMethod, string $sEndPoint, array $aParameters = []): stdClass
    {
        //  @todo (Pablo - 2019-06-12) - Handle HTTP method
        //  @todo (Pablo - 2019-06-12) - Handle payload

        $sUrl = sprintf(
                static::API_URL,
                $this->getDataCenter(),
                $this->getApiVersion()
            ) . $sEndPoint;

        $oCurl = curl_init($sUrl);
        switch ($sMethod) {
            case 'POST':
                $sData = json_encode($aParameters);
                curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sData);
                curl_setopt($oCurl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($sData),
                ]);
                break;
            default:
                break;
        }

        curl_setopt($oCurl, CURLOPT_USERPWD, 'apikey:' . $this->sApiKey);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);

        $oResponse   = json_decode(curl_exec($oCurl));
        $iReturnCode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);

        curl_close($oCurl);

        if ($iReturnCode === 401) {
            throw new UnauthorisedException(
                $oResponse->detail
            );
        } elseif ($iReturnCode !== 200) {

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
     * @param string $sEndPoint
     * @param array  $aParameters
     *
     * @return stdClass
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function get(string $sEndPoint, array $aParameters = []): stdClass
    {
        return $this->call('GET', $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a POST request
     *
     * @param string $sEndPoint
     * @param array  $aParameters
     *
     * @return stdClass
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function post(string $sEndPoint, array $aParameters = []): stdClass
    {
        return $this->call('POST', $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a DELETE request
     *
     * @param string $sEndPoint
     * @param array  $aParameters
     *
     * @return stdClass
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function delete(string $sEndPoint, array $aParameters = []): stdClass
    {
        return $this->call('DELETE', $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Executes a PUT request
     *
     * @param string $sEndPoint
     * @param array  $aParameters
     *
     * @return stdClass
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function put(string $sEndPoint, array $aParameters = []): stdClass
    {
        return $this->call('PUT', $sEndPoint, $aParameters);
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the Lists interface
     *
     * @return Lists
     */
    public function lists(): Lists
    {
        return $this->oListsInstance;
    }
}

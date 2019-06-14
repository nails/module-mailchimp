<?php

namespace Nails\MailChimp\Factory\Api;

use Nails\Common\Helper\ArrayHelper;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use Nails\MailChimp\Factory\Api\Lists\Member;
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

    const HTTP_METHOD_GET    = 'GET';
    const HTTP_METHOD_POST   = 'POST';
    const HTTP_METHOD_PATCH  = 'PATCH';
    const HTTP_METHOD_DELETE = 'DELETE';

    // --------------------------------------------------------------------------

    /**
     * The instance of the lists class
     *
     * @var Lists
     */
    protected $oListsInstance;

    /**
     * The instance of the member class
     *
     * @var Member
     */
    protected $oMemberInstance;

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
     * @param array  $aConfig         The config array
     * @param Member $oMemberInstance The instance of the Member class
     * @param Lists  $oListsInstance  The instance of the Lists class
     */
    public function __construct(
        array $aConfig = [],
        Member $oMemberInstance = null,
        Lists $oListsInstance = null
    ) {
        if (is_null($oMemberInstance)) {
            $this->oMemberInstance = new Member();
        }
        $this->oMemberInstance->setClient($this);

        if (is_null($oListsInstance)) {
            $this->oListsInstance = new Lists();
        }
        $this->oListsInstance->setClient($this);
        $this->oListsInstance->setMember($this->oMemberInstance);

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
     * @return stdClass|null
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function call(string $sMethod, string $sEndPoint, array $aParameters = []): ?stdClass
    {
        $sUrl = sprintf(
                static::API_URL,
                $this->getDataCenter(),
                $this->getApiVersion()
            ) . $sEndPoint;

        $oCurl = curl_init($sUrl);

        curl_setopt($oCurl, CURLOPT_CUSTOMREQUEST, $sMethod);

        switch ($sMethod) {
            case static::HTTP_METHOD_GET:
                break;
            case static::HTTP_METHOD_POST:
            case static::HTTP_METHOD_PATCH:
                $sData = json_encode($aParameters);
                curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sData);
                curl_setopt($oCurl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($sData),
                ]);
                break;
            case static::HTTP_METHOD_DELETE:
                break;
        }

        curl_setopt($oCurl, CURLOPT_USERPWD, 'apikey:' . $this->sApiKey);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);

        $sResponse   = curl_exec($oCurl);
        $oResponse   = json_decode($sResponse);
        $iReturnCode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);

        curl_close($oCurl);

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
     * @throws UnauthorisedException
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
     * @throws UnauthorisedException
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
     * @throws UnauthorisedException
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
     * @throws UnauthorisedException
     */
    public function patch(string $sEndPoint, array $aParameters = []): ?stdClass
    {
        return $this->call(static::HTTP_METHOD_PATCH, $sEndPoint, $aParameters);
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

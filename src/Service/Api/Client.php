<?php

namespace Nails\MailChimp\Service\Api;

use Nails\Common\Helper\ArrayHelper;

/**
 * Class Client
 *
 * @package Nails\MailChimp\Api\Service
 */
class Client
{
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
     * @param array $aConfig The config array
     */
    public function __construct(array $aConfig = [])
    {
        //  @todo (Pablo - 2019-06-12) - Default to database values?
        $this->sDataCenter = ArrayHelper::getFromArray('data_center', $aConfig);
        $this->sApiKey     = ArrayHelper::getFromArray('api_key', $aConfig);
        $this->sApiKey     = ArrayHelper::getFromArray('api_key', $aConfig);
        $this->sApiVersion = ArrayHelper::getFromArray('api_version', $aConfig);
    }
}

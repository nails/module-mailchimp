<?php

namespace Nails\MailChimp\Tests\Factory\Api;

use Nails\MailChimp\Factory\Api\Client;
use Nails\MailChimp\Factory\Api\Lists;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @package Nails\MailChimp\Tests\Factory\Api
 */
final class ClientTest extends TestCase
{
    public function test_client_uses_defaults()
    {
        $oClient = new Client();

        $this->assertEquals($oClient::DEFAULT_DATA_CENTER, $oClient->getDataCenter());
        $this->assertEquals($oClient::DEFAULT_API_KEY, $oClient->getApiKey());
        $this->assertEquals($oClient::DEFAULT_API_VERSION, $oClient->getApiVersion());
    }

    // --------------------------------------------------------------------------

    public function test_can_configure_client()
    {
        $aConfig = [
            'data_center' => 'my-data-center',
            'api_key'     => 'my-api-key',
            'api_version' => 'my-api-version',
        ];

        $oClient = new Client($aConfig);

        $this->assertEquals($aConfig['data_center'], $oClient->getDataCenter());
        $this->assertEquals($aConfig['api_key'], $oClient->getApiKey());
        $this->assertEquals($aConfig['api_version'], $oClient->getApiVersion());
    }

    // --------------------------------------------------------------------------

    public function test_client_returns_instance_of_lists()
    {
        $oClient = new Client();
        $oLists  = $oClient->lists();

        $this->assertInstanceOf(Lists::class, $oLists);
        $this->assertSame($oClient, $oLists->getClient());
    }
}

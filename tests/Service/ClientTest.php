<?php

namespace Nails\MailChimp\Tests\Service;

use Nails\Common\Exception\FactoryException;
use Nails\Config;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Service\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @package Nails\MailChimp\Tests\Service
 */
final class ClientTest extends TestCase
{
    /** @var Client */
    private static $oClient;

    // --------------------------------------------------------------------------

    public function test_client_uses_defaults()
    {
        $oClient = new Client();

        $this->assertEquals($oClient::DEFAULT_API_URL, $oClient->getApiUrl());
        $this->assertEquals($oClient::DEFAULT_DATA_CENTER, $oClient->getDataCenter());
        $this->assertEquals($oClient::DEFAULT_API_KEY, $oClient->getApiKey());
        $this->assertEquals('3.0', $oClient->getApiVersion());
    }

    // --------------------------------------------------------------------------

    public function test_test_credentials_are_set()
    {
        $this->assertNotEmpty(getenv('TEST_DATA_CENTER'));
        $this->assertNotEmpty(getenv('TEST_API_KEY'));
    }

    // --------------------------------------------------------------------------

    public function test_can_configure_client()
    {
        //  These values will be used by the other tests
        Config::set('MAILCHIMP_DATA_CENTER', getenv('TEST_DATA_CENTER'));
        Config::set('MAILCHIMP_API_KEY', getenv('TEST_API_KEY'));
        $oClient = new Client();

        $this->assertEquals(Config::get('MAILCHIMP_DATA_CENTER'), $oClient->getDataCenter());
        $this->assertEquals(Config::get('MAILCHIMP_API_KEY'), $oClient->getApiKey());
    }

    // --------------------------------------------------------------------------

    /**
     * @throws FactoryException
     */
    public function test_factory_loads_instance_of_client()
    {
        static::$oClient = Factory::service('Client', Constants::MODULE_SLUG);
        $this->assertInstanceOf(Client::class, static::$oClient);
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::call
     */
    public function test_client_has_method_call()
    {
        $this->assertTrue(method_exists(static::$oClient, 'call'));
    }

    // -------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::get
     */
    public function test_client_has_method_get()
    {
        $this->assertTrue(method_exists(static::$oClient, 'get'));
    }

    // -------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::post
     */
    public function test_client_has_method_post()
    {
        $this->assertTrue(method_exists(static::$oClient, 'post'));
    }

    // -------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::delete
     */
    public function test_client_has_method_delete()
    {
        $this->assertTrue(method_exists(static::$oClient, 'delete'));
    }

    // -------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::patch
     */
    public function test_client_has_method_patch()
    {
        $this->assertTrue(method_exists(static::$oClient, 'patch'));
    }

    // -------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::audience
     */
    public function test_client_has_method_audience()
    {
        $this->assertTrue(method_exists(static::$oClient, 'audience'));
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::members
     */
    public function test_client_has_method_members()
    {
        $this->assertTrue(method_exists(static::$oClient, 'members'));
    }
}

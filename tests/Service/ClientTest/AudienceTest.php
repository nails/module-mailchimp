<?php

namespace Nails\MailChimp\Tests\Service\ClientTest;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Resource;
use Nails\MailChimp\Service\Client;
use Nails\MailChimp\Factory\Audience;
use PHPUnit\Framework\TestCase;

/**
 * Class AudienceTest
 *
 * @package Nails\MailChimp\Tests\Service\ClientTest
 */
final class AudienceTest extends TestCase
{
    /** @var Client */
    private static $oClient;

    /** @var Resource\Audience */
    private static $oAudience;

    // --------------------------------------------------------------------------

    /**
     * @throws FactoryException
     */
    public static function setUpBeforeClass(): void
    {
        static::$oClient = Factory::service('Client', Constants::MODULE_SLUG);
        parent::setUpBeforeClass();
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::audience
     * @throws FactoryException
     */
    public function test_client_returns_instance_of_audience_factory()
    {
        $this->assertInstanceOf(
            Audience::class,
            static::$oClient->audiences()
        );
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Audience::create
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_create_audience()
    {
        static::$oAudience = static::createAudience(static::$oClient);
        $this->assertInstanceOf(Resource\Audience::class, static::$oAudience);
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Audience::getAll
     * @throws FactoryException
     * @throws ApiException
     */
    public function test_can_list_audiences()
    {
        $aAudiences = static::$oClient
            ->audiences()
            ->getAll();

        $this->assertIsArray($aAudiences);
        $this->assertNotEmpty($aAudiences);
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Audience::getById
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_get_audience_by_id()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('No audience set');
        } else {

            $oAudience = static::$oClient
                ->audiences()
                ->getById(static::$oAudience->id);

            $this->assertNotEmpty($oAudience);
            $this->assertInstanceOf(Resource\Audience::class, $oAudience);
            $this->assertEquals(static::$oAudience->id, $oAudience->id);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Audience::getById
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_error_on_invalid_audience_id()
    {
        $this->expectException(ApiException::class);
        static::$oClient
            ->audiences()
            ->getById('invalid-id');
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Audience::update
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_update_audience()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('No audience set');
        } else {

            $oAudience = static::$oClient
                ->audiences()
                ->update(
                    static::$oAudience->id,
                    [
                        'name' => 'Updated',
                    ]
                );

            $this->assertNotEmpty($oAudience);
            $this->assertInstanceOf(Resource\Audience::class, $oAudience);
            $this->assertEquals(static::$oAudience->id, $oAudience->id);
            $this->assertEquals('Updated', $oAudience->name);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Audience::delete
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_delete_audience()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('No audience set');
        } else {

            static::deleteAudience(static::$oClient, static::$oAudience);

            $this->expectException(ApiException::class);
            static::$oClient
                ->audiences()
                ->getById(static::$oAudience->id);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Helper method: Create an audience (used by other tests)
     *
     * @param Client $oClient The client to use
     *
     * @return Resource\Audience
     * @throws ApiException
     * @throws FactoryException
     */
    public static function createAudience(Client $oClient): Resource\Audience
    {
        $oNow = Factory::factory('DateTime');
        return $oClient
            ->audiences()
            ->create([
                'name'                => 'Test List - ' . $oNow->format('Y-m-d H:i:s'),
                'contact'             => [
                    'company'  => 'An Example Company',
                    'address1' => '123 Main street',
                    'city'     => 'Glasgow',
                    'state'    => 'Glasgow',
                    'zip'      => 'G20 8LR',
                    'country'  => 'Scotland',
                ],
                'permission_reminder' => 'This is a test list, you were added by mistake',
                'campaign_defaults'   => [
                    'from_name'  => 'Test Name',
                    'from_email' => 'module-mailchimp@nailsapp.co.uk',
                    'subject'    => 'This is a test',
                    'language'   => 'en-gb',
                ],
                'email_type_option'   => false,
            ]);
    }

    // --------------------------------------------------------------------------

    /**
     * Helper method: Delete an audience (used by other tests)
     *
     * @param Client            $oClient   The client to use
     * @param Resource\Audience $oAudience The audience to delete
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public static function deleteAudience(Client $oClient, Resource\Audience $oAudience)
    {
        $oClient
            ->audiences()
            ->delete($oAudience->id);
    }
}

<?php

namespace Nails\MailChimp\Tests\Factory\Api;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use Nails\MailChimp\Factory\Api\Client;
use Nails\MailChimp\Resource\MailChimpList;
use PHPUnit\Framework\TestCase;

/**
 * Class ListsTest
 *
 * @package Nails\MailChimp\Tests\Factory\Api
 */
final class ListsTest extends TestCase
{
    /**
     * @var Client
     */
    private static $oClient;

    /**
     * The ID of the list which is created by the tests
     *
     * @var MailChimpList
     */
    private static $oList;

    // --------------------------------------------------------------------------

    /**
     * Sets up the Client
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$oClient = new Client([
            'data_center' => getenv('MAILCHIMP_DATA_CENTER'),
            'api_key'     => getenv('MAILCHIMP_API_KEY'),
            'api_version' => getenv('MAILCHIMP_API_VERSION'),
        ]);
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_create_list()
    {
        $oNow  = Factory::factory('DateTime');
        $oList = static::$oClient->lists()->create([
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

        $this->assertInstanceOf(MailChimpList::class, $oList);
        $this->assertNotEmpty($oList->id);

        static::$oList = $oList;
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function test_can_list_lists()
    {
        $aLists = static::$oClient->lists()->getAll();

        $this->assertIsArray($aLists);
        $this->assertNotEmpty($aLists);
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_get_list_by_id()
    {
        if (empty(static::$oList)) {
            $this->addWarning('No list ID to fetch');
        } else {

            $oList = static::$oClient->lists()->getById(static::$oList->id);

            $this->assertNotEmpty($oList);
            $this->assertInstanceOf(MailChimpList::class, $oList);
            $this->assertEquals(static::$oList->id, $oList->id);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_update_list()
    {
        if (empty(static::$oList)) {
            $this->addWarning('No list ID to update');
        } else {

            $oList = static::$oClient->lists()->update(
                static::$oList->id,
                [
                    'name' => 'Updated',
                ]
            );

            $this->assertNotEmpty($oList);
            $this->assertInstanceOf(MailChimpList::class, $oList);
            $this->assertEquals(static::$oList->id, $oList->id);
            $this->assertEquals('Updated', $oList->name);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_delete_list()
    {
        if (empty(static::$oList)) {
            $this->addWarning('No list ID to delete');
        } else {

            static::$oClient->lists()->delete(static::$oList->id);

            $this->expectException(ApiException::class);
            static::$oClient->lists()->getById(static::$oList->id);
        }
    }
}

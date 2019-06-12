<?php

namespace Nails\MailChimp\Tests\Factory\Api;

use Nails\Factory;
use Nails\MailChimp\Factory\Api\Client;
use Nails\MailChimp\Resource\MailChimpList;
use PHPUnit\Framework\TestCase;

/**
 * Class ListsTest
 *
 * @package Nails\MailChimp\Tests\Factory\Api
 */
class ListsTest extends TestCase
{
    /**
     * @var Client
     */
    private static $oClient;

    // --------------------------------------------------------------------------

    private $sCreatedListId;

    // --------------------------------------------------------------------------

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

    public function test_can_list_lists()
    {
        $aLists = static::$oClient->lists()->getAll();

        $this->assertIsArray($aLists);
        $this->assertNotEmpty($aLists);
    }

    // --------------------------------------------------------------------------

    public function test_can_get_list_by_id()
    {
        $aLists       = static::$oClient->lists()->getAll();
        $oDesiredList = reset($aLists);
        $oList        = static::$oClient->lists()->getById($oDesiredList->id);

        $this->assertNotEmpty($oList);
        $this->assertInstanceOf(MailChimpList::class, $oDesiredList);
        $this->assertEquals($oDesiredList->id, $oList->id);
    }

    // --------------------------------------------------------------------------

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
    }

    // --------------------------------------------------------------------------

    public function test_can_delete_list()
    {
        $this->doesNotPerformAssertions();
        if (empty($this->sCreatedListId)) {
            $this->addWarning('No list ID to delete');
        } else {
            static::$oClient->lists()->delete($this->sCreatedListId);
        }
    }
}

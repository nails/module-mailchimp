<?php

namespace Nails\MailChimp\Tests\Factory\Api\Lists;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Exception\Api\UnauthorisedException;
use Nails\MailChimp\Factory\Api\Client;
use Nails\MailChimp\Resource\MailChimpList;
use PHPUnit\Framework\TestCase;

/**
 * Class MemberTest
 *
 * @package Nails\MailChimp\Tests\Factory\Api
 */
final class MemberTest extends TestCase
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
     * The created list member
     *
     * @var MailChimpList\Member
     */
    private static $oMember;

    // --------------------------------------------------------------------------

    /**
     * @throws FactoryException
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$oClient = new Client([
            'data_center' => getenv('MAILCHIMP_DATA_CENTER'),
            'api_key'     => getenv('MAILCHIMP_API_KEY'),
            'api_version' => getenv('MAILCHIMP_API_VERSION'),
        ]);

        $oNow          = Factory::factory('DateTime');
        static::$oList = static::$oClient->lists()->create([
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
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public static function tearDownAfterClass(): void
    {
        static::$oClient->lists()->delete(static::$oList->id);
        parent::tearDownAfterClass();
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_create_member()
    {
        $oNow            = Factory::factory('DateTime');
        $sEmail          = 'module-mailchimp-' . $oNow->format('YmdHis') . '@nailsapp.co.uk';
        static::$oMember = static::$oList
            ->members()
            ->create([
                'email_address' => $sEmail,
                'status'        => 'subscribed',
            ]);

        $this->assertInstanceOf(MailChimpList\Member::class, static::$oMember);
        $this->assertNotEmpty(static::$oMember->id);
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws UnauthorisedException
     */
    public function test_can_list_members()
    {
        $aMembers = static::$oList
            ->members()
            ->getAll();

        $this->assertIsArray($aMembers);
        $this->assertNotEmpty($aMembers);
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_get_member_by_id()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('No member ID to fetch');
        } else {

            $oMember = static::$oList->members()->getById(static::$oMember->id);

            $this->assertNotEmpty($oMember);
            $this->assertInstanceOf(MailChimpList\Member::class, $oMember);
            $this->assertEquals(static::$oMember->id, $oMember->id);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_update_member()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('No member ID to update');
        } else {

            $oMember = static::$oList
                ->members()
                ->update(
                    static::$oMember->id,
                    [
                        'status' => 'unsubscribed',
                    ]
                );

            $this->assertNotEmpty($oMember);
            $this->assertInstanceOf(MailChimpList\Member::class, $oMember);
            $this->assertEquals(static::$oMember->id, $oMember->id);
            $this->assertEquals('unsubscribed', $oMember->status);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     * @throws UnauthorisedException
     */
    public function test_can_delete_member()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('No member ID to update');
        } else {
            static::$oList
                ->members()
                ->delete(static::$oMember->id);

            $this->expectException(ApiException::class);
            static::$oList->members()->getById(static::$oMember->id);
        }
    }
}

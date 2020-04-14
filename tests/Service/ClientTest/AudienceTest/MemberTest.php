<?php

namespace Nails\MailChimp\Tests\Service\ClientTest\AudienceTest;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Factory\Member;
use Nails\MailChimp\Resource;
use Nails\MailChimp\Service\Client;
use Nails\MailChimp\Tests\Service\ClientTest\AudienceTest;
use PHPUnit\Framework\TestCase;

/**
 * Class MemberTest
 *
 * @package Nails\MailChimp\Tests\Service\ClientTest\AudienceTest
 */
final class MemberTest extends TestCase
{
    /** @var Client */
    private static $oClient;

    /** @var Resource\Audience */
    private static $oAudience;

    /** @var Resource\Member */
    private static $oMember;

    // --------------------------------------------------------------------------

    /**
     * @throws FactoryException
     * @throws ApiException
     */
    public static function setUpBeforeClass(): void
    {
        static::$oClient   = Factory::service('Client', Constants::MODULE_SLUG);
        static::$oAudience = AudienceTest::createAudience(static::$oClient);
        parent::setUpBeforeClass();
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     */
    public static function tearDownAfterClass(): void
    {
        if (!empty(static::$oAudience)) {
            AudienceTest::deleteAudience(static::$oClient, static::$oAudience);
        }
        parent::tearDownAfterClass();
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Service\Client::members
     * @throws FactoryException
     * @throws ApiException
     */
    public function test_client_returns_instance_of_members_factory()
    {
        $this->assertInstanceOf(
            Member::class,
            static::$oClient->members(static::$oAudience->id)
        );
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Resource\Audience::members
     */
    public function test_audience_has_method_members()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } else {
            $this->assertTrue(
                method_exists(static::$oAudience, 'members')
            );
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Resource\Audience::members
     * @throws FactoryException
     */
    public function test_audience_members_method_returns_instance_of_member_factory()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } else {
            $this->assertInstanceOf(
                Member::class,
                static::$oAudience->members()
            );
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Resource\Audience::members
     * @throws FactoryException
     */
    public function test_member_factory_is_configured_with_audience()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } else {
            $oMemberFactory = static::$oAudience->members();
            $this->assertInstanceOf(
                Resource\Audience::class,
                $oMemberFactory->getAudience()
            );
            $this->assertEquals(
                static::$oAudience->id,
                $oMemberFactory->getAudience()->id
            );
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::create
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_create_member()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } else {

            static::$oMember = static::createMember(static::$oAudience);

            $this->assertInstanceOf(Resource\Member::class, static::$oMember);
            $this->assertNotEmpty(static::$oMember->id);
            $this->assertEquals('subscribed', static::$oMember->status);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::buildSubscriberHash
     */
    public function test_correct_subscriber_hash_is_generated()
    {
        $sEmailWithCaseAndWhitespace = ' Bugs.Bunny@example.com ';
        $this->assertEquals(
            '63120b7d057612bbdc5753fd0d230d4c',
            Member::buildSubscriberHash($sEmailWithCaseAndWhitespace)
        );
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::getAll
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_list_members()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } else {

            $aMembers = static::$oAudience
                ->members()
                ->getAll();

            $this->assertIsArray($aMembers);
            $this->assertNotEmpty($aMembers);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::getByEmail
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_get_member_by_email()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } else {

            $oMember = static::$oAudience
                ->members()
                ->getByEmail(static::$oMember->email_address);

            $this->assertNotEmpty($oMember);
            $this->assertInstanceOf(Resource\Member::class, $oMember);
            $this->assertEquals(static::$oMember->id, $oMember->id);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::getById
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_error_on_invalid_member_id()
    {
        $this->expectException(ApiException::class);
        static::$oAudience
            ->members()
            ->getByEmail('invalid-email@example.com');
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::update
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_update_member()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } elseif (empty(static::$oAudience)) {
            $this->addWarning('Member not set');
        } else {

            $oMember = static::$oAudience
                ->members()
                ->update(
                    static::$oMember->email_address,
                    [
                        'email_type' => 'html',
                    ]
                );

            $this->assertNotEmpty($oMember);
            $this->assertInstanceOf(Resource\Member::class, $oMember);
            $this->assertEquals(static::$oMember->id, $oMember->id);
            $this->assertEquals('html', $oMember->email_type);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::archive
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_archive_member()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } elseif (empty(static::$oAudience)) {
            $this->addWarning('Member not set');
        } else {

            static::$oAudience
                ->members()
                ->archive(static::$oMember->email_address);

            $oMember = static::$oAudience
                ->members()
                ->getByEmail(static::$oMember->email_address);

            $this->assertEquals('archived', $oMember->status);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::unarchive
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_unarchive_member()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } elseif (empty(static::$oAudience)) {
            $this->addWarning('Member not set');
        } else {

            static::$oAudience
                ->members()
                ->unarchive(static::$oMember->email_address);

            $oMember = static::$oAudience
                ->members()
                ->getByEmail(static::$oMember->email_address);

            $this->assertNotEmpty($oMember);
            $this->assertInstanceOf(Resource\Member::class, $oMember);
            $this->assertEquals(static::$oMember->id, $oMember->id);
            $this->assertEquals('subscribed', $oMember->status);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Factory\Member::delete
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_delete_member()
    {
        if (empty(static::$oAudience)) {
            $this->addWarning('Audience not set');
        } elseif (empty(static::$oAudience)) {
            $this->addWarning('Member not set');
        } else {

            static::deleteMember(static::$oAudience, static::$oMember);

            $this->expectException(ApiException::class);
            static::$oAudience
                ->members()
                ->getByEmail(static::$oMember->email_address);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Helper method: Creates a new audience member (used by other tests)
     *
     * @param Resource\Audience $oAudience The audience to add the member to
     *
     * @return Resource\Member
     * @throws ApiException
     * @throws FactoryException
     */
    public static function createMember(Resource\Audience $oAudience): Resource\Member
    {
        $oNow   = Factory::factory('DateTime');
        $sEmail = 'module-mailchimp-' . $oNow->format('YmdHis') . '@nailsapp.co.uk';

        return $oAudience
            ->members()
            ->create([
                'email_address' => $sEmail,
                'email_type'    => 'text',
                'status'        => 'subscribed',
            ]);
    }

    // --------------------------------------------------------------------------

    /**
     * Helper method: Deletes a member from an audience (used by other tests)
     *
     * @param Resource\Audience $oAudience The audience to delete the member from
     * @param Resource\Member   $oMember   The member to delete
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public static function deleteMember(Resource\Audience $oAudience, Resource\Member $oMember)
    {
        $oAudience
            ->members()
            ->delete($oMember->email_address);
    }
}

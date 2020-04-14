<?php

namespace Nails\MailChimp\Tests\Service\ClientTest\AudienceTest\MemberTest;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Factory\Tag;
use Nails\MailChimp\Resource;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Service\Client;
use Nails\MailChimp\Tests\Service\ClientTest\AudienceTest;
use PHPUnit\Framework\TestCase;

/**
 * Class TagTest
 *
 * @package Nails\MailChimp\Tests\Service\ClientTest\AudienceTest\MemberTest
 */
final class TagTest extends TestCase
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
        static::$oMember   = AudienceTest\MemberTest::createMember(static::$oAudience);
        parent::setUpBeforeClass();
    }

    // --------------------------------------------------------------------------

    /**
     * @throws ApiException
     * @throws FactoryException
     */
    public static function tearDownAfterClass(): void
    {
        if (!empty(static::$oAudience) && !empty(static::$oMember)) {
            AudienceTest\MemberTest::deleteMember(static::$oAudience, static::$oMember);
        }
        if (!empty(static::$oAudience)) {
            AudienceTest::deleteAudience(static::$oClient, static::$oAudience);
        }
        parent::tearDownAfterClass();
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Resource\Member::tags
     */
    public function test_member_has_method_tags()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {
            $this->assertTrue(
                method_exists(static::$oMember, 'tags')
            );
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Resource\Member::tags
     * @throws FactoryException
     */
    public function test_audience_tags_method_returns_instance_of_tag_factory()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {
            $this->assertInstanceOf(
                Tag::class,
                static::$oMember->tags()
            );
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers \Nails\MailChimp\Resource\Member::tags
     * @throws FactoryException
     */
    public function test_tag_factory_is_configured_with_member()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {

            $oTagFactory = static::$oMember->tags();
            $this->assertInstanceOf(
                Resource\Member::class,
                $oTagFactory->getMember()
            );
            $this->assertEquals(
                static::$oMember->id,
                $oTagFactory->getMember()->id
            );
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers  \Nails\MailChimp\Factory\Tag::add
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_add_tags()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {

            $this->expectNotToPerformAssertions();
            static::$oMember
                ->tags()
                ->add(['Test Tag']);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers  \Nails\MailChimp\Factory\Tag::getAll
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_list_tags()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {

            $aTags = static::$oMember
                ->tags()
                ->getAll();

            $this->assertNotEmpty($aTags);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers  \Nails\MailChimp\Factory\Tag::remove
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_remove_tags()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {

            static::$oMember
                ->tags()
                ->remove(['Test Tag']);

            $aTags = static::$oMember
                ->tags()
                ->getAll();

            $this->assertEmpty($aTags);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * @covers  \Nails\MailChimp\Factory\Tag::set
     * @throws ApiException
     * @throws FactoryException
     */
    public function test_can_set_tags()
    {
        if (empty(static::$oMember)) {
            $this->addWarning('Member not set');
        } else {

            static::$oMember
                ->tags()
                ->add(['Test Tag']);

            $aTags = static::$oMember
                ->tags()
                ->getAll();

            $this->assertNotEmpty($aTags);
            $this->assertCount(1, $aTags);

            $oTag = reset($aTags);
            $this->assertEquals('Test Tag', $oTag->name);

            static::$oMember
                ->tags()
                ->set([
                    ['name' => 'Test Tag', 'status' => 'inactive'],
                    ['name' => 'New Tag', 'status' => 'active'],
                ]);

            $aTags = static::$oMember
                ->tags()
                ->getAll();

            $this->assertNotEmpty($aTags);
            $this->assertCount(1, $aTags);

            $oTag = reset($aTags);
            $this->assertEquals('New Tag', $oTag->name);
        }
    }
}

<?php

/**
 * MailChimp Tag Factory
 *
 * @package     Nails
 * @subpackage  module-mailchimp
 * @category    Factory
 * @author      Nails Dev Team
 * @link        https://docs.nailsapp.co.uk/modules/other/mailchimp
 */

namespace Nails\MailChimp\Factory;

use Nails\Common\Exception\FactoryException;
use Nails\Factory;
use Nails\MailChimp\Constants;
use Nails\MailChimp\Exception\Api\ApiException;
use Nails\MailChimp\Resource;
use Nails\MailChimp\Service\Client;
use stdClass;

/**
 * Class Tag
 *
 * @package Nails\MailChimp\Factory
 */
class Tag
{
    /**
     * The client to use
     *
     * @var Client
     */
    protected $oClient;

    /**
     * The member to use
     *
     * @var Resource\Member
     */
    protected $oMember;

    // --------------------------------------------------------------------------

    /**
     * Tag constructor.
     *
     * @param Client          $oClient The client to use
     * @param Resource\Member $oMember The audience to use
     */
    public function __construct(Client $oClient, Resource\Member $oMember)
    {
        $this->oClient = $oClient;
        $this->oMember = $oMember;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the configured member
     *
     * @return Resource\Member
     */
    public function getMember(): Resource\Member
    {
        return $this->oMember;
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the endpoint URL
     *
     * @return string
     */
    protected function buildEndpoint()
    {
        return sprintf(
            'lists/%s/members/%s/tags',
            $this->getMember()->getAudience()->id,
            $this->getMember()->getHash()
        );
    }

    // --------------------------------------------------------------------------

    /**
     * Builds the Tag resource
     *
     * @param stdClass $oTag The tag data
     *
     * @return Resource\Member\Tag
     * @throws FactoryException
     */
    protected function buildResource(stdClass $oTag): Resource\Member\Tag
    {
        /** @var Resource\Member\Tag $oResource */
        $oResource = Factory::resource('MemberTag', Constants::MODULE_SLUG, $oTag);
        return $oResource;
    }

    // --------------------------------------------------------------------------

    /**
     * Adds member tags
     *
     * @param string[] $aTags An array of tags to add
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function add(array $aTags): void
    {
        $this->oClient
            ->post(
                $this->buildEndpoint(),
                [
                    'tags' => array_map(
                        function ($sTag) {
                            return [
                                'name'   => $sTag,
                                'status' => 'active',
                            ];
                        },
                        $aTags
                    ),
                ]
            );
    }

    // --------------------------------------------------------------------------

    /**
     * Removes member tags
     *
     * @param string[] $aTags An array of tags to remove
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function remove(array $aTags): void
    {
        $this->oClient
            ->post(
                $this->buildEndpoint(),
                [
                    'tags' => array_map(
                        function ($sTag) {
                            return [
                                'name'   => $sTag,
                                'status' => 'inactive',
                            ];
                        },
                        $aTags
                    ),
                ]
            );
    }

    // --------------------------------------------------------------------------

    /**
     * Sets the member's tags
     *
     * @param array[] $aTags Array of Tags; [['name' => 'tag', 'status' => '[in]active']]
     *
     * @throws ApiException
     * @throws FactoryException
     */
    public function set(array $aTags): void
    {
        $this->oClient
            ->post(
                $this->buildEndpoint(),
                ['tags' => $aTags]
            );
    }

    // --------------------------------------------------------------------------

    /**
     * @return Resource\Member\Tag[]
     * @throws FactoryException
     * @throws ApiException
     */
    public function getAll(): array
    {
        $oResponse = $this->oClient
            ->get(
                $this->buildEndpoint()
            );

        return array_map(
            function (stdClass $oTag) {
                return $this->buildResource($oTag);
            },
            $oResponse->tags
        );
    }
}

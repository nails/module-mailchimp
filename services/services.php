<?php
/**
 * Define your module's services, models and factories.
 *
 * @link http://nailsapp.co.uk/docs/services
 */

use Nails\MailChimp\Factory;
use Nails\MailChimp\Resource;
use Nails\MailChimp\Service;

return [
    /**
     * Classes/libraries which don't necessarily relate to a database table.
     * Once instantiated, a request for a service will always return the same instance.
     */
    'services'  => [
        'Client' => function (): Service\Client {
            if (class_exists('\App\MailChimp\Service\Client')) {
                return new \App\MailChimp\Service\Client();
            } else {
                return new Service\Client();
            }
        },
    ],

    /**
     * Models generally represent database tables.
     * Once instantiated, a request for a model will always return the same instance.
     */
    'models'    => [],

    /**
     * A class for which a new instance is created each time it is requested.
     */
    'factories' => [
        'Audience' => function (Service\Client $oClient) {
            if (class_exists('\App\MailChimp\Factory\Audience')) {
                return new \App\MailChimp\Factory\Audience($oClient);
            } else {
                return new Factory\Audience($oClient);
            }
        },
        'Member'   => function (Service\Client $oClient, Resource\Audience $oAudience) {
            if (class_exists('\App\MailChimp\Factory\Member')) {
                return new \App\MailChimp\Factory\Member($oClient, $oAudience);
            } else {
                return new Factory\Member($oClient, $oAudience);
            }
        },
        'Tag'      => function (Service\Client $oClient, Resource\Member $oMember) {
            if (class_exists('\App\MailChimp\Factory\Tag')) {
                return new \App\MailChimp\Factory\Tag($oClient, $oMember);
            } else {
                return new Factory\Tag($oClient, $oMember);
            }
        },
    ],

    /**
     * A class which represents an object from the database
     */
    'resources' => [
        'Audience'                 => function ($resource, Service\Client $oClient): Resource\Audience {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Audience')) {
                return new \App\MailChimp\Resource\Audience($resource, $oClient);
            } else {
                return new Resource\Audience($resource, $oClient);
            }
        },
        'AudienceCampaignDefaults' => function ($resource, $model = null): Resource\Audience\CampaignDefaults {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Audience\CampaignDefaults')) {
                return new \App\MailChimp\Resource\Audience\CampaignDefaults($resource);
            } else {
                return new Resource\Audience\CampaignDefaults($resource);
            }
        },
        'AudienceContact'          => function ($resource, $model = null): Resource\Audience\Contact {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Audience\Contact')) {
                return new \App\MailChimp\Resource\Audience\Contact($resource);
            } else {
                return new Resource\Audience\Contact($resource);
            }
        },
        'AudienceLink'             => function ($resource, $model = null): Resource\Audience\Link {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Audience\Link')) {
                return new \App\MailChimp\Resource\Audience\Link($resource);
            } else {
                return new Resource\Audience\Link($resource);
            }
        },
        'AudienceModule'           => function ($resource, $model = null): Resource\Audience\Module {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Audience\Module')) {
                return new \App\MailChimp\Resource\Audience\Module($resource);
            } else {
                return new Resource\Audience\Module($resource);
            }
        },
        'AudienceStats'            => function ($resource, $model = null): Resource\Audience\Stats {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Audience\Stats')) {
                return new \App\MailChimp\Resource\Audience\Stats($resource);
            } else {
                return new Resource\Audience\Stats($resource);
            }
        },
        'Member'                   => function (
            //  @todo (Pablo 2025-07-15) - this should be a factory
            $resource,
            Service\Client $oClient,
            Resource\Audience $oAudience
        ): Resource\Member {
            if (class_exists('\App\MailChimp\Resource\Member')) {
                return new \App\MailChimp\Resource\Member($resource, $oClient, $oAudience);
            } else {
                return new Resource\Member($resource, $oClient, $oAudience);
            }
        },
        'MemberLink'               => function ($resource, $model = null): Resource\Member\Link {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Member\Link')) {
                return new \App\MailChimp\Resource\Member\Link($resource);
            } else {
                return new Resource\Member\Link($resource);
            }
        },
        'MemberLocation'           => function ($resource, $model = null): Resource\Member\Location {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Member\Location')) {
                return new \App\MailChimp\Resource\Member\Location($resource);
            } else {
                return new Resource\Member\Location($resource);
            }
        },
        'MemberMergeFields'        => function ($resource, $model = null): Resource\Member\MergeFields {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Member\MergeFields')) {
                return new \App\MailChimp\Resource\Member\MergeFields($resource);
            } else {
                return new Resource\Member\MergeFields($resource);
            }
        },
        'MemberStats'              => function ($resource, $model = null): Resource\Member\Stats {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Member\Stats')) {
                return new \App\MailChimp\Resource\Member\Stats($resource);
            } else {
                return new Resource\Member\Stats($resource);
            }
        },
        'MemberTag'                => function ($resource, $model = null): Resource\Member\Tag {
            //  @todo (Pablo 2025-07-15) - this should be a factory
            if (class_exists('\App\MailChimp\Resource\Member\Tag')) {
                return new \App\MailChimp\Resource\Member\Tag($resource);
            } else {
                return new Resource\Member\Tag($resource);
            }
        },
    ],
];

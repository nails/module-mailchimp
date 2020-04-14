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
        'Audience'                 => function ($oObj, Service\Client $oClient): Resource\Audience {
            if (class_exists('\App\MailChimp\Resource\Audience')) {
                return new \App\MailChimp\Resource\Audience($oObj, $oClient);
            } else {
                return new Resource\Audience($oObj, $oClient);
            }
        },
        'AudienceCampaignDefaults' => function ($oObj): Resource\Audience\CampaignDefaults {
            if (class_exists('\App\MailChimp\Resource\Audience\CampaignDefaults')) {
                return new \App\MailChimp\Resource\Audience\CampaignDefaults($oObj);
            } else {
                return new Resource\Audience\CampaignDefaults($oObj);
            }
        },
        'AudienceContact'          => function ($oObj): Resource\Audience\Contact {
            if (class_exists('\App\MailChimp\Resource\Audience\Contact')) {
                return new \App\MailChimp\Resource\Audience\Contact($oObj);
            } else {
                return new Resource\Audience\Contact($oObj);
            }
        },
        'AudienceLink'             => function ($oObj): Resource\Audience\Link {
            if (class_exists('\App\MailChimp\Resource\Audience\Link')) {
                return new \App\MailChimp\Resource\Audience\Link($oObj);
            } else {
                return new Resource\Audience\Link($oObj);
            }
        },
        'AudienceModule'           => function ($oObj): Resource\Audience\Module {
            if (class_exists('\App\MailChimp\Resource\Audience\Module')) {
                return new \App\MailChimp\Resource\Audience\Module($oObj);
            } else {
                return new Resource\Audience\Module($oObj);
            }
        },
        'AudienceStats'            => function ($oObj): Resource\Audience\Stats {
            if (class_exists('\App\MailChimp\Resource\Audience\Stats')) {
                return new \App\MailChimp\Resource\Audience\Stats($oObj);
            } else {
                return new Resource\Audience\Stats($oObj);
            }
        },
        'Member'                   => function (
            $oObj,
            Service\Client $oClient,
            Resource\Audience $oAudience
        ): Resource\Member {
            if (class_exists('\App\MailChimp\Resource\Member')) {
                return new \App\MailChimp\Resource\Member($oObj, $oClient, $oAudience);
            } else {
                return new Resource\Member($oObj, $oClient, $oAudience);
            }
        },
        'MemberLink'               => function ($oObj): Resource\Member\Link {
            if (class_exists('\App\MailChimp\Resource\Member\Link')) {
                return new \App\MailChimp\Resource\Member\Link($oObj);
            } else {
                return new Resource\Member\Link($oObj);
            }
        },
        'MemberLocation'           => function ($oObj): Resource\Member\Location {
            if (class_exists('\App\MailChimp\Resource\Member\Location')) {
                return new \App\MailChimp\Resource\Member\Location($oObj);
            } else {
                return new Resource\Member\Location($oObj);
            }
        },
        'MemberMergeFields'        => function ($oObj): Resource\Member\MergeFields {
            if (class_exists('\App\MailChimp\Resource\Member\MergeFields')) {
                return new \App\MailChimp\Resource\Member\MergeFields($oObj);
            } else {
                return new Resource\Member\MergeFields($oObj);
            }
        },
        'MemberStats'              => function ($oObj): Resource\Member\Stats {
            if (class_exists('\App\MailChimp\Resource\Member\Stats')) {
                return new \App\MailChimp\Resource\Member\Stats($oObj);
            } else {
                return new Resource\Member\Stats($oObj);
            }
        },
        'MemberTag'                => function ($oObj): Resource\Member\Tag {
            if (class_exists('\App\MailChimp\Resource\Member\Tag')) {
                return new \App\MailChimp\Resource\Member\Tag($oObj);
            } else {
                return new Resource\Member\Tag($oObj);
            }
        },
    ],
];

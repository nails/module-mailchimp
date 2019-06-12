<?php
/**
 * Define your module's services, models and factories.
 *
 * @link http://nailsapp.co.uk/docs/services
 */

return [
    /**
     * Classes/libraries which don't necessarily relate to a database table.
     * Once instantiated, a request for a service will always return the same instance.
     */
    'services'  => [
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
        'ApiClient'       => function () {
            if (class_exists('\App\MailChimp\Factory\Api\Client')) {
                return new \App\MailChimp\Factory\Api\Client();
            } else {
                return new \Nails\MailChimp\Factory\Api\Client();
            }
        },
        'ApiLists'        => function () {
            if (class_exists('\App\MailChimp\Factory\Api\Lists')) {
                return new \App\MailChimp\Factory\Api\Lists();
            } else {
                return new \Nails\MailChimp\Factory\Api\Lists();
            }
        },
        'ApiListsMember'  => function () {
            if (class_exists('\App\MailChimp\Factory\Api\Lists\Member')) {
                return new \App\MailChimp\Factory\Api\Lists\Member();
            } else {
                return new \Nails\MailChimp\Factory\Api\Lists\Member();
            }
        },
        'ApiListsSegment' => function () {
            if (class_exists('\App\MailChimp\Factory\Api\Lists\Segment')) {
                return new \App\MailChimp\Factory\Api\Lists\Segment();
            } else {
                return new \Nails\MailChimp\Factory\Api\Lists\Segment();
            }
        },
    ],

    /**
     * A class which represents an object from the database
     */
    'resources' => [
        'List' => function ($oObj) {
            if (class_exists('\App\MailChimp\Resource\MailChimpList')) {
                return new \App\MailChimp\Resource\MailChimpList($oObj);
            } else {
                return new \Nails\MailChimp\Resource\MailChimpList($oObj);
            }
        },
    ],
];

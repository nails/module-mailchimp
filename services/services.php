<?php
/**
 * Define your module's services, models and factories.
 *
 * @link http://nailsapp.co.uk/docs/services
 */

use Nails\MailChimp\Service\Api;

return [
    /**
     * Classes/libraries which don't necessarily relate to a database table.
     * Once instantiated, a request for a service will always return the same instance.
     */
    'services'  => [
        'ApiClient'       => function () {
            if (class_exists('\App\MailChimp\Service\Api\Client')) {
                return new \App\MailChimp\Service\Api\Client();
            } else {
                return new Api\Client();
            }
        },
        'ApiLists'        => function () {
            if (class_exists('\App\MailChimp\Service\Api\Lists')) {
                return new \App\MailChimp\Service\Api\Lists();
            } else {
                return new Api\Lists();
            }
        },
        'ApiListsMember'  => function () {
            if (class_exists('\App\MailChimp\Service\Api\Lists\Member')) {
                return new \App\MailChimp\Service\Api\Lists\Member();
            } else {
                return new Api\Lists\Member();
            }
        },
        'ApiListsSegment' => function () {
            if (class_exists('\App\MailChimp\Service\Api\Lists\Segment')) {
                return new \App\MailChimp\Service\Api\Lists\Segment();
            } else {
                return new Api\Lists\Segment();
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
    'factories' => [],

    /**
     * A class which represents an object from the database
     */
    'resources' => [],
];

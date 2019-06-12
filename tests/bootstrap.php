<?php

use Nails\Components;
use Nails\Factory;

require 'vendor/autoload.php';

//  Configure Nails so we can use the Factory
define('NAILS_APP_PATH', getcwd() . DIRECTORY_SEPARATOR);
define('BASEPATH', getcwd() . '/vendor/codeigniter/framework/system' . DIRECTORY_SEPARATOR);
define('NAILS_CI_SYSTEM_PATH', BASEPATH);

Components::$oAppSlug      = 'nails/module-mailchimp';
Components::$oAppNamespace = '\\Nails\\MailChimp\\';
Factory::$oAppSlug         = 'nails/module-mailchimp';

Factory::setup();
Factory::autoload();

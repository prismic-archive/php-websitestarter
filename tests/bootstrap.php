<?php

// HTML Parser
include 'ganon.php';

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

use There4\Slim\Test\WebTestCase;

define('PROJECT_ROOT', realpath(__DIR__.'/..'));

require_once PROJECT_ROOT.'/vendor/autoload.php';
require_once PROJECT_ROOT.'/app/includes/PrismicHelper.php';

class LocalWebTestCase extends WebTestCase
{
    public function getConfig()
    {
        return array(
            'version'        => '0.0.0',
            'debug'          => false,
            'mode'           => 'testing',
            'site.title'     => 'Blog Template',
            'prismic.url'    => 'https://blogtemplate.prismic.io/api',
            'theme'          => 'bootstrap',
        );
    }

    public function getSlimInstance()
    {
        $app = new \Slim\Slim($this->getConfig());
        $prismic = new PrismicHelper($app);

        global $WPGLOBAL;
        $WPGLOBAL = array(
            'app' => $app,
            'prismic' => $prismic,
        );

        // Include our core application file
        require PROJECT_ROOT.'/app/app.php';

        return $app;
    }
};

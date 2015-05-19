<?php

require_once '../vendor/autoload.php';
require_once '../app/includes/PrismicHelper.php';

// config.php is not present by default, so we show a message explaining to create one
if (file_exists('../config.php')) {
    include '../config.php';
} else {
    include '../app/includes/templates/firstrun.php';
    exit();
}

$composer = json_decode(file_get_contents(__DIR__.'/../composer.json'));
$app = new \Slim\Slim(
    array(
      'version'        => $composer->version,
      'debug'          => DEBUG,
      'mode'           => MODE,
      'prismic.url'    => PRISMIC_URL,
      'prismic.token'  => PRISMIC_TOKEN,
      'site.title'     => SITE_TITLE,
      'site.description' => SITE_DESCRIPTION,
      'page_size'      => PAGE_SIZE,
      'disqus.forum'  => DISQUS_FORUM,
      'disqus.apikey'  => DISQUS_API_KEY,
      'disqus.apisecret'  => DISQUS_API_SECRET,
      'disqus.accesstoken'  => DISQUS_API_ACCESSTOKEN,
      'mailgun.apikey'  => MAILGUN_APIKEY,
      'mailgun.pubkey'  => MAILGUN_PUBKEY,
      'mailgun.domain'  => MAILGUN_DOMAIN,
      'mailgun.email'  => MAILGUN_EMAIL,
    )
);
$prismic = new PrismicHelper($app);

global $WPGLOBAL;
$WPGLOBAL = array(
    'app' => $app,
    'prismic' => $prismic,
);

require_once __DIR__.'/../app/app.php';

$app->run();
